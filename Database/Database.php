<?php

/**
 * Class Database
 */
class Database
{
    // set connection instance
    public static $DBinstance = null;

    // set the database variables
    protected $host = 'localhost';
    protected $username = 'root';
    protected $password = '';
    protected $database = "NoughtsAndCrosses";

    /**
     * Function used to create and connect to DB
     */
    public function __construct()
    {
        // it checks to see if there isn't already connection instance
        if (!self::$DBinstance) {
            try {
                // creates a new PDO mySQL connection with params
                self::$DBinstance = new PDO(
                    "mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
                self::$DBinstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // if there is a problem it catches the exception and displays a custom message with the error.
            } catch (PDOException $exception) {
                echo "Oops. You've a Connection Error: " . $exception->getMessage();
            }
        }
        return self::$DBinstance;
    }

    /**
     * Function used to insert the match into the database
     *
     * @param string $winner
     * @param string $result
     */
    public function insert(string $winner, string $result)
    {
        // prepares the query to execute an insertion
        $query = self::$DBinstance->prepare(
            "INSERT INTO matches (winner, results) VALUES ({$winner}, {$result})");

        $sql = self::$DBinstance->query($query);
        // sets the fetch mode
        $sql->fetch(PDO::FETCH_ASSOC);
        // executes the prepared statement
        $sql->execute();
    }

    /**
     * Function used to count all results
     *
     * @param string $table
     * @param string $column
     *
     * @return array
     */
    public function count(string $table, string $column)
    {
        $query = "SELECT {$column}, COUNT(*) FROM {$table} GROUP BY {$column}";

        $sql = self::$DBinstance->query($query);
        // sets the default fetch mode
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        // fetches the next row from outputted results
        $sql->fetch();

        while($row = $sql) {
            $output[] = $row;
        }
        return $output;
    }
}
