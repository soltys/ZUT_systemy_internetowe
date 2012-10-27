<?php

require_once dirname(dirname(__FILE__)) . './config.php';
require_once ABSPATH . 'klogger/klogger.php';
require_once ABSPATH . 'model/Person.php';
define('AUTH_SUFFIX', 'auth_');
define('LOGGED_KEY', AUTH_SUFFIX . 'logged_userId');
define('LOGGED_RIGHTS_KEY', AUTH_SUFFIX . 'logged_rights');

class SessionManager {

    private $log;
    private static $oInstance = false;

    public static function getInstance() {
        if (self::$oInstance == false) {
            self::$oInstance = new SessionManager();
        }
        return self::$oInstance;
    }

    private function __construct() {

        $this->log = KLogger::instance(ABSPATH . "logs/SessionManager", KLOGGER_ERROR_LEVEL);
        $hr = session_start();
        if (!$hr) {
            $this->log->logFatal("Unable to start a seesion");
        }
    }

    public function getLoggedUserId() {
        if (isset($_SESSION[LOGGED_KEY])) {
            $userId = $_SESSION[LOGGED_KEY];
            return $userId;
        }
        return NULL;
    }

    public function setLoggedUserId($userId) {
        $_SESSION[LOGGED_KEY] = $userId;
    }

    public function getUserRights() {
        if (isset($_SESSION[LOGGED_RIGHTS_KEY])) {
            $rights = $_SESSION[LOGGED_RIGHTS_KEY];
            return $rights;
        }
        return NULL;
    }

    public function setUserRights($rights) {
        $_SESSION[LOGGED_RIGHTS_KEY] = $rights;
    }

    private function getSessionArray($key) {
        if (isset($_SESSION[$key])) {
            $sessionArray = $_SESSION[$key];
        } else {
            $sessionArray = array();
        }
        return $sessionArray;
    }

    public function addPerson($person) {
        $peopleList = $this->getSessionArray("people");
        array_push($peopleList, $person);
        $_SESSION['people'] = $peopleList;
        $this->log->logInfo("added new entry to session");
    }

    public function getPeople() {
        return $this->getSessionArray("people");
    }

    public function getPeopleCount() {
        $peopleList = $this->getSessionArray("people");
        return count($peopleList);
    }

}

?>
