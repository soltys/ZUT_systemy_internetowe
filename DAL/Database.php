<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . "klogger/klogger.php";
require_once ABSPATH . "model/Person.php";
require_once ABSPATH . "model/User.php";
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
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $personTableResult = mysqli_query($this->mysqli, $query);
        if (!$personTableResult) {
            $this->log->logAlert("Query failed to create a table `" . PERSON_TABLE . "` in database");
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
            $this->log->logAlert("Query failed to create a table `" . USER_TABLE . "` in database");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    /**
     *
     * @param type $row
     * @return Person
     */
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

    private function createUser($row) {
        $userId = $row["userId"];
        $login = $row["login"];
        $password = $row["password"];
        $rights = $row["rights"];
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];

        $user = new User($login, $password, $rights, $firstName, $lastName, $userId);
        return $user;
    }

    public function addPerson($person) {
        $firstName = $person->getFirstName();
        $lastName = $person->getLastName();
        $gender = $person->getGender(true);
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

    /**
     *
     * @param Person $person
     */
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
            $this->log->logAlert("Query failed update person from database, where personId is {$person->getPersonId()}, and query is " . $query);
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

// USER

    /**
     * @return Array Array of users
     */
    public function getUsers() {
        $result = mysqli_query($this->mysqli, "SELECT * FROM " . USER_TABLE);
        if (!$result) {
            $this->log->logAlert("Query failed select all users from database");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
            return array();
        }
        $userList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $user = $this->createUser($row);
            array_push($userList, $user);
        }
        mysqli_free_result($result);
        return $userList;
    }

    /**
     * @param User $user
     */
    public function addUser($user) {
        $login = $user->getLogin();
        $password = $user->getPassword();
        $rights = $user->getRights();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();

        $result = mysqli_query($this->mysqli, "INSERT INTO " . USER_TABLE . " (login, password, rights, firstName, lastName)
VALUES ('$login','$password', $rights,'$firstName', '$lastName')");
        if (!$result) {
            $this->log->logAlert("Query failed to add person to database");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    /**
     * @param User $user
     */
    public function updateUser($user) {
        $query = "UPDATE " . USER_TABLE . " SET firstName='{$user->getFirstName()}',
                                        lastName='{$user->getLastName()}',
                                        login='{$user->getLogin()}',
                                        password='{$user->getPassword()}'
                                        WHERE userId={$user->getUserId()}";

        $result = mysqli_query($this->mysqli, $query);
        if (!$result) {
            $this->log->logAlert("Query failed update user from database, where personId is {$user->getUserId()}, and query is " . $query);
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    /**
     *
     * @param int $userId
     * @param int $newRights
     */
    public function updateRights($userId, $newRights) {
        $query = "UPDATE " . USER_TABLE . " SET rights=$newRights
                                        WHERE userId=$userId";

        $result = mysqli_query($this->mysqli, $query);
        if (!$result) {
            $this->log->logAlert("Query failed update user rights in database, where personId is $userId, and query is " . $query);
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    /**
     *
     * @param int $userId
     */
    public function deleteUser($userId) {
        $result = mysqli_query($this->mysqli, "DELETE FROM " . USER_TABLE . " WHERE `userId`=$userId");

        if (!$result) {
            $this->log->logAlert("Query failed delete user from database, where userId is $userId");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }
    }

    public function getUserByLogin($login) {
        $result = mysqli_query($this->mysqli, "SELECT * FROM " . USER_TABLE . " WHERE login='$login'");
        if (!$result) {
            $this->log->logAlert("Query failed select users from database, where login is $login");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }

        $row = mysqli_fetch_assoc($result);
        $user = $this->createUser($row);
        return $user;
    }

    public function getUser($userId) {
        $result = mysqli_query($this->mysqli, "SELECT * FROM " . USER_TABLE . " WHERE userId=$userId");
        if (!$result) {
            $this->log->logAlert("Query failed select user from database, where userId is $userId");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }

        $row = mysqli_fetch_assoc($result);
        $user = $this->createUser($row);
        return $user;
    }

    public function isLoginExists($login) {
        $result = mysqli_query($this->mysqli, "SELECT * FROM " . USER_TABLE . " WHERE login='$login'");
        $rowsCount = mysqli_num_rows($result);
        if (!$result) {
            $this->log->logAlert("Query failed select users from database, where login is $login");
            $this->log->logAlert("Error: %s\n", mysqli_error($this->mysqli));
        }

        if ($rowsCount == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkCredentials($login, $password) {
        $user = $this->getUserByLogin($login);
        if ($user->getPassword() == $password) {
            return TRUE;
        } else {
            $this->log->logAlert("Failed to login with $login and $password");
            return FALSE;
        }
    }

    public function __destruct() {
        mysqli_close($this->mysqli);
    }

}

?>
