<?php

require_once dirname(dirname(__FILE__)) . './config.php';
require_once ABSPATH . 'klogger/klogger.php';
require_once ABSPATH . 'model/Person.php';
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
            $log->logFatal("Unable to start a seesion");
        }
    }

    private function getSessionData($key) {
        if (isset($_SESSION[$key])) {
            $sessionArray = $_SESSION[$key];
        } else {
            $sessionArray = array();
        }
        return $sessionArray;
    }

    public function addPerson($person) {
        $peopleList = $this->getSessionData("people");
        array_push($peopleList, $person);
        $_SESSION['people'] = $peopleList;
        $this->log->logInfo("added new entry to session");
    }

    public function getPeople() {
        return $this->getSessionData("people");
    }

    public function getPeopleCount() {
        $peopleList = $this->getSessionData("people");
        return count($peopleList);
    }

}

?>
