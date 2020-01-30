<?php
require_once('abstractDAO.php');
require_once('./model/Customer.php');

class customerDAO extends abstractDAO
{

    /*
     *  customerDAO constructor. Uses parent class abstractDAO constructor to establish connection to database
     */
    function __construct()
    {
        try {
            parent::__construct();
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    /*
     * Returns an array of <code>Customer</code> objects. If no customers exist, returns false.
     * This function is an example of using the query() method of a mysqli object
     */
    public function getCustomers()
    {
        $result = $this->myslqi->query('SELECT * FROM mailingList');    //the query method returns a mysqli_result object
        $customers = Array();
        if ($result->num_rows >= 1) {    // if query result returns at least 1 row
            while ($row = $result->fetch_assoc()) {  // fetch_assoc() returns the next row query result as an associative array which is then stored in $row
                // Create a new customer object, and add it to the array
                $customer = new Customer($row['customerName'], $row['phoneNumber'], $row['emailAddress'], $row['referrer']);
                $customers[] = $customer;
            }
            $result->free();    // clears the memory holding the query result
            return $customers;
        }
        // if query result returns no rows. return false
        $result->free();
        return false;
    }

    /*
     * Returns parameter specified customer from wp_eatery database mailingList Table.
     * This function is an example of using a prepared statement with a created query
     */
    public function getCustomer($_id)
    {
        $query = 'SELECT * FROM mailinglist WHERE _id = ?'; // query uses ? as a placeholder for the parameters to be used in the query
        $stmt = $this->myslqi->prepare($query);
        $stmt->bind_param('i', $_id);   // bind_param first parameter is a string describing the data type. 'i' represents integer. Second parameter are the corresponding variables
        $stmt->execute();   // execute the statement query
        $result = $stmt->get_result();  // returns a result set from the query execution. This is a mysqli_result object
        if ($result->num_rows == 1) {    // if the result query returns at least 1 row with matching customer id
            $temp = $result->fetch_assoc(); // store the query result rows in a temporary variable
            $customer = new Customer($temp['customerName'], $temp['phoneNumber'], $temp['emailAddress'], $temp['referrer']);
            $result->free();    // clears the memory holding the query result
            return $customer;
        }
        $result->free();
        return false;
    }

    public function getID($emailAddress)
    {
        $result = $this->myslqi->query('SELECT * FROM mailinglist');
        if ($result->num_rows >= 1) {   // if result is found
            while ($row = $result->fetch_assoc()) {
                if ($row['emailAddress'] == $emailAddress) {
                    return $row['_id'];
                }
            }
            $result->free();
        }
        return false;
    }

    /*
     * Adds the parameter customer to the wp_eatery sql database mailingList Table
     */
    public function addCustomer(Customer $customer)
    {
        $customerName = $customer->getCustomerName();
        $phoneNumber = $customer->getPhoneNumber();
        $emailAddress = password_hash($customer->getEmailAddress(), PASSWORD_DEFAULT);
        $referrer = $customer->getReferrer();
        if (!$this->myslqi->connect_errno) {    // if no error occurs connecting to sql database
            $query = 'INSERT INTO mailinglist(customerName, phoneNumber, emailAddress, referrer) VALUES (?,?,?,?)';  // query uses ? as a placeholder for the parameters to be used in the query
            $stmt = $this->myslqi->prepare($query); // prepare method of mysqli object returns a mysqli_stmt object. It takes a parameterized query as a parameter
            // bind_param first parameter is a string describing the data type. 's' represents string. Second parameter are the corresponding variables
            $stmt->bind_param('ssss',
                $customerName,
                $phoneNumber,
                $emailAddress,
                $referrer);
            $stmt->execute();   // execute the statement
            if ($stmt->error) { // if error occurs executing the statement, return the error
                return $stmt->error;
            } else {
                return $customer->getCustomerName() . ' added successfully!';
            }
        } else {
            return 'Could not connect to Database.';
        }
    }

    /*
     * Deletes the parameter customer from the wp_eatery database mailingList Table
     */
    public function deleteCustomer($_id)
    {
        if (!$this->myslqi->connect_errno) {    // if no error occurs connecting to the sql database
            $query = 'DELETE FROM mailinglist WHERE _id = ?';
            $stmt = $this->myslqi->prepare($query);
            $stmt->bind_param('i', $_id);
            $stmt->execute();
            if ($stmt->error) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /*
     * Checks if parameter email already exists in database. Returns true if it does
     */
    public function checkDuplicateEmail($emailAddress)
    {
        if (!$this->myslqi->connect_errno) {    // if no error occurs connecting to the sql database
            $result = $this->myslqi->query('SELECT emailAddress FROM mailinglist');
            if ($result->num_rows >= 1) {   // if a result is returned from query, check if email duplicate exists
                while ($row = $result->fetch_assoc()) {
                    if (password_verify($emailAddress,$row['emailAddress'])) {
                        $result->free();
                        return true;
                    }
                }
                return false;
            }
        }
    }

    /*
     * Edit the cutsomer's data specified in the parameters in the wp_eatery database mailingList Table
     */
    public function editCustomer($_id, $customerName, $phoneNumber, $emailAddress, $referrer)
    {
        $hashedEmailAddress = password_hash($emailAddress, PASSWORD_DEFAULT);
        if (!$this->myslqi->connect_errno) {    // if no error occurs connecting to the sql database
            $query = 'UPDATE mailingList SET customerName = ?, phoneNumber = ?, emailAddress = ?, referrer = ? WHERE _id = ?';
            $stmt = $this->myslqi->prepare($query);
            $stmt->bind_param('ssssi',
                $customerName,
                $phoneNumber,
                $hashedEmailAddress,
                $referrer,
                $_id);
            $stmt->execute();
            if ($stmt->error) {
                return false;
            } else {
                return $stmt->affected_rows;    // returns the number of affected rows by the executed query statement
            }
        } else {
            return false;
        }
    }
}

?>


