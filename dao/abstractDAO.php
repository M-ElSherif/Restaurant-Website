<?php

//Used to throw mysqli_sql_exceptions for database
//errors instead or printing them to the screen.
mysqli_report(MYSQLI_REPORT_STRICT);


/**
 * Abstract data access class. Holds all of the database
 * connection information, and initializes a mysqli object
 * on instantiation.
 *
 * @author Mohamed El Sherif
 */
class abstractDAO
{
    /* Represents a connection between PHP and a MySQL database. */
    protected $myslqi;
    /* Host address for the database */
    protected static $DB_HOST = "127.0.0.1";
    /* Database username */
    protected static $DB_USERNAME = "wp_eatery";
    /* Database password */
    protected static $DB_PASSWORD = "password";
    /* Name of database */
    protected static $DB_DATABASE = "wp_eatery";

    function __construct()
    {
        try {
            $this->myslqi = new mysqli(self::$DB_HOST,self::$DB_USERNAME,self::$DB_PASSWORD,self::$DB_DATABASE);
        }
        catch(mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public function getMysqli() {
        return $this->myslqi;
    }
}