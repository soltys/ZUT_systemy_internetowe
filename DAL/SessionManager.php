<?php

require_once dirname(__FILE__).'/../klogger/klogger.php';

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

        $log = KLogger::instance(dirname(__FILE__)."/../logs/SessionManager");
        $hr = session_start();
        if (!$hr) {
            $log->logFatal("Unable to start a seesion");
        }
    }

    public function addPerson($person) {
        if (isset($_SESSION['people'])) {
            $peopleList = $_SESSION['people'];
        } else {
            $peopleList = array();
        }
        array_push($peopleList, $person);
        $_SESSION['people'] = $peopleList;
    }

}

?>
