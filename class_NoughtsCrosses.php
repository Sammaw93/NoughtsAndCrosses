<?php

class NoughtsCrosses
{
    /** @var Database */
    protected $database;

    /** @var int */
    protected $xWins = 0;
    /** @var int */
    protected $oWins = 0;
    /** @var int */
    protected $draws = 0;


    // All the winning combinations keys into an array
    /** @var array */
    protected $winning_inputs = [
        0 => [0, 1, 2],
        1 => [3, 4, 5],
        2 => [6, 7, 8],
        3 => [0, 3, 6],
        4 => [1, 4, 7],
        5 => [2, 5, 8],
        6 => [0, 4, 8],
        7 => [2, 4, 6]
    ];

    /**
     * NoughtsCrosses constructor.
     */
    public function __construct()
    {
        // creates a new database on object creation
        $this->database = new Database();
    }

    /**
     * Function which is used to all overall results
     */
    public function get_aggregate_results()
    {
        $countResults = $this->database->count('matches', 'winner');
        // Loops through all the counted results
        foreach ($countResults as $count) {
            // if the winner column value is a draw, count the total draws and print them
            if ($count['winner'] == 'draw') {
                echo "Draws: " . $count['COUNT(*)'] . PHP_EOL;
                // if the winner column contains an X or O as the winner print total
            } else {
                echo $count['winner'] . "Wins: " . $count['COUNT(*)'] . PHP_EOL;
            }
        }
    }

    /**
     * @param string $input
     */
    public function calculate_winners(string $input)
    {
        $result = str_replace(' ', '', $input);

        // set the winner to null
        $winner = null;
        // I can loop through all of these and take the value from the result array with the same key
        // and put it into a new array.
        foreach ($this->winning_inputs as $combination_key => $winning_line) {
            $line = [];
            foreach ($winning_line as $key) {
                $line[] = $result[$key];
            }
            // I can then use array_unique to find out if we have the same value in every value of that array
            // if we do we have determined a winning row and we know the winner is the value in that array
            if (count(array_unique($line)) === 1) {
                $winner = $line[0];
                // we can then break out of the loop since we found the winner
                break;
            }
        }
        if ($winner) {
            $this->database->insert($winner, $result);
            $this->{strtolower($winner) . 'Wins'}++;
            // if there's no winner after the loop then we have a draw
        } else {
            $this->draws++;
        }
    }

    /**
     * Function used to print out the total wins for each
     */
    public function get_results()
    {
        // prints out the results
        echo "X Wins: " . $this->xWins . PHP_EOL;
        echo "O Wins: " . $this->oWins . PHP_EOL;
        echo "Draws: " . $this->draws . PHP_EOL;
    }
}
