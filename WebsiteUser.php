<?php
/**
 * webisteUser class
 * creates new website User objects that have a username, password, lastlogin date and time, database errors
 * authentication status and a mysqli connection with PHP
 **/

class WebsiteUser{

    /* Host address for the wp-eatery database */
    protected static $DB_HOST = "localhost";
    /* wp_eatery database username */
    protected static $DB_USERNAME = "wp_eatery";
    /* wp_eatery database password */
    protected static $DB_PASSWORD = "password";
    /* Name of wp_eatery database */
    protected static $DB_DATABASE = "wp_eatery";

    private $mysqli;
    private $adminID;
    private $username;
    private $password;
    private $lastlogin;
    private $dbError;
    private $authenticated = false;

    /*
     * Constructs a new WebsiteUser object and establishes sql connection
     */
    function __construct() {
        $this->mysqli = new mysqli(self::$DB_HOST, self::$DB_USERNAME,
            self::$DB_PASSWORD, self::$DB_DATABASE);
        if($this->mysqli->errno){
            $this->dbError = true;
        }else{
            $this->dbError = false;
        }
    }

    /*
     * Authenticates the argument username and password
     */
    public function authenticate($username, $password){
        $loginQuery = "SELECT * from adminusers WHERE Username=? and Password=?";
        $stmt = $this->mysqli->prepare($loginQuery);
        $stmt->bind_param('ss', $username,$password);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows==1){   // if the query result returns a corresponding row with parameter username and password
            $temp = $result->fetch_assoc(); // store the result assosciative array of adminuser info into a array variable
            $this->setAdminID($temp['AdminID']);
            $this->setAuthenticated(true);
            $this->setUsername($username);
            $this->setPassword($password);
            $this->updateLastLogin($username);  // update the last login date of argument user
        }
        $stmt->free_result();
    }

    /*
     * Updates the last login date for the argument user
     */
    public function updateLastLogin($username) {
        $query = 'UPDATE adminusers SET Lastlogin=? WHERE Username=?';  // query to update the lastlogin date on argument username
        $stmt = $this->mysqli->prepare($query);
        $date = date('m/d/Y');
        $stmt->bind_param('ss', $date, $username);
        $stmt->execute();   // execute the query
        $this->setLastlogin($date); // sets the last login date of the website user after updating it in adminusers table
        $stmt->free_result();   // free stored result memory
    }

    public function isAuthenticated(){
        return $this->authenticated;
    }

    public function hasDbError(){
        return $this->dbError;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getID(){
        return $this->adminID;
    }

    public function getDate(){
        return $this->lastlogin;
    }


    public function setAdminID($adminID)
    {
        $this->adminID = $adminID;
    }


    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function setLastlogin($lastlogin)
    {
        $this->lastlogin = $lastlogin;
    }


    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;
    }

}

?>