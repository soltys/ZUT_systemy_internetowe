<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . "klogger/klogger.php";
require_once ABSPATH . "model/Person.php";
define('TABLE_PREFIX', "si_");
define('PERSON_TABLE', TABLE_PREFIX . 'person');
define('USER_TABLE', TABLE_PREFIX . 'user');

class Database {

    private $mysqli;
    private $log;

    function __construct() {
        $this->log = KLogger::instance(ABSPATH . "logs/Database", KLOGGER_ERROR_LEVEL);
        $this->mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if (mysqli_connect_errno($this->mysqli)) {
            $this->log->logFatal("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        $this->createTables();
    }

    private function createTables() {
        $query = "CREATE TABLE IF NOT EXISTS `" . PERSON_TABLE . "` (
                `personId` int(11) NOT NULL AUTO_INCREMENT,
                `firstName` varchar(256) NOT NULL,
                `lastName` varchar(256) NOT NULL,
                `gender` varchar(256) NOT NULL,
                `maidenName` varchar(256) NOT NULL,
                `email` varchar(256) NOT NULL,
                `postalCode` varchar(256) NOT NULL,
                PRIMARY KEY (`personId`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;";
        $personTableResult = mysqli_query($this->mysqli, $query);
        if (!$personTableResult) {
            $this->log->logAlert("Query failed to create a table `" . PERSON_TABLE . "` to database");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }

        $query = "CREATE TABLE IF NOT EXISTS `" . USER_TABLE . "` (
                `userId` int(11) NOT NULL AUTO_INCREMENT,
                `login` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `rights` int(11) NOT NULL,
                `firstName` varchar(255) NOT NULL,
                `lastName` varchar(255) NOT NULL,
                PRIMARY KEY (`userId`),
                UNIQUE KEY `login` (`login`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
        $userTableResult = mysqli_query($this->mysqli, $query);
        if (!$userTableResult) {
            $this->log->logAlert("Query failed to create a table `" . USER_TABLE . "` to database");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    public function addPerson($person) {
        $firstName = $person->getFirstName();
        $lastName = $person->getLastName();
        $gender = $person->getGender();
        $maidenName = $person->getMaidenName();
        $email = $person->getEmail();
        $postalCode = $person->getPostalCode();
        $result = mysqli_query($this->mysqli, "INSERT INTO " . PERSON_TABLE . " (firstName, lastName, gender, maidenName, email, postalCode)
VALUES ('$firstName', '$lastName', '$gender', '$maidenName', '$email', '$postalCode')");
        if (!$result) {
            $this->log->logAlert("Query failed to add person to database");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    private function createPerson($row) {
        $personId = $row["personId"];
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
        $gender = $row["gender"];
        $maidenName = $row["maidenName"];
        $email = $row["email"];
        $postalCode = $row["postalCode"];

        $person = new Person($firstName, $lastName, $gender, $maidenName, $email, $postalCode, $personId);
        return $person;
    }

    public function getPeople() {
        $result = mysqli_query($this->mysqli, "SELECT * FROM " . PERSON_TABLE);
        if (!$result) {
            $this->log->logAlert("Query failed select all people from database");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
            return array();
        }
        $peopleList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $person = $this->createPerson($row);
            array_push($peopleList, $person);
        }
        mysqli_free_result($result);
        return $peopleList;
    }

    public function searchPeople($field, $query) {
        $result = mysqli_query($this->mysqli, "SELECT * FROM " . PERSON_TABLE . " WHERE `$field` LIKE '%$query%'");
        if (!$result) {
            $this->log->logAlert("Query failed select people from database, where $field is like $query");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
            return array();
        }
        $peopleList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $person = $this->createPerson($row);
            array_push($peopleList, $person);
        }
        mysqli_free_result($result);
        return $peopleList;
    }

    public function getPerson($personId) {
        $result = mysqli_query($this->mysqli, "SELECT * FROM " . PERSON_TABLE . " WHERE personId=$personId");
        $row = mysqli_fetch_assoc($result);
        $person = $this->createPerson($row);
        if (!$result) {
            $this->log->logAlert("Query failed select person from database, where personId is $personId");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
        mysqli_free_result($result);
        return $person;
    }

    public function updatePerson($person) {
        $query = "UPDATE " . PERSON_TABLE . " SET firstName='{$person->getFirstName()}',
                                        lastName='{$person->getLastName()}',
                                        gender='{$person->getGender(true)}',
                                        maidenName='{$person->getMaidenName()}',
                                        email='{$person->getEmail()}',
                                        postalCode='{$person->getPostalCode()}'
                                        WHERE personId={$person->getPersonId()}";

        $result = mysqli_query($this->mysqli, $query);
        if (!$result) {
            $this->log->logAlert("Query failed update person from database, where personId is $personId, and query is " . $query);
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    public function deletePerson($personId) {
        $result = mysqli_query($this->mysqli, "DELETE FROM " . PERSON_TABLE . " WHERE `personId`=$personId");

        if (!$result) {
            $this->log->logAlert("Query failed delete person from database, where personId is $personId");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    public function __destruct() {
        mysqli_close($this->mysqli);
    }

}

?>
