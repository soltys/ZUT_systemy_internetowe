<?php
require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . "klogger/klogger.php";
require_once ABSPATH . "model/Person.php";
define('PERSON_TABLE','si_person');
class Database{
    private $mysqli;
    private $log;

    function __construct() {
        $this->log = KLogger::instance(ABSPATH . "logs/Database",KLOGGER_ERROR_LEVEL);
        $this->mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if(mysqli_connect_errno($this->mysqli))
        {
            $this->log->logFatal("Failed to connect to MySQL: " . mysqli_connect_error());
        }
    }
}
?>
