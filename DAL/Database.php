<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . "klogger/klogger.php";
require_once ABSPATH . "model/Person.php";
define('PERSON_TABLE', 'si_person');

class Database {

    private $mysqli;
    private $log;

    function __construct() {
        $this->log = KLogger::instance(ABSPATH . "logs/Database", KLOGGER_ERROR_LEVEL);
        $this->mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if (mysqli_connect_errno($this->mysqli)) {
            $this->log->logFatal("Failed to connect to MySQL: " . mysqli_connect_error());
        }
    }

    public function getPersonTable() {
        $peopleList = array();
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
            $this->log->logAlert("Query failed to add new person to database");
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

    public function __destruct() {
        mysqli_close($this->mysqli);
    }

}

?>
