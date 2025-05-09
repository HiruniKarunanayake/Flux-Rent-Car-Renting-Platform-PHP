<?php

class Database
{
    public static $connection;

    // Set up the database connection
    public static function setUpConnection()
    {
        if (!isset(self::$connection)) {
            self::$connection = new mysqli("localhost", "root", "", "fluxrent");

            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
        }
    }

    // Execute Insert, Update, Delete queries
    public static function iud($q)
    {
        self::setUpConnection();
        if (self::$connection->query($q) === TRUE) {
            return true;
        } else {
            // Return an error message if the query fails
            return "Error: " . self::$connection->error;
        }
    }

    // Execute search/select queries
    public static function search($q)
    {
        self::setUpConnection();
        $result = self::$connection->query($q);
        if ($result === FALSE) {
            // Return an error message if the query fails
            die("Error: " . self::$connection->error);
        }
        return $result;
    }
}