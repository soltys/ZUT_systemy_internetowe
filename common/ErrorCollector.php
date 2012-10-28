<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . 'klogger/klogger.php';

class ErrorCollector {

    private $errors;
    private $log;

    function __construct($viewName = NULL) {
        $this->errors = array();

        if (!isset($viewName)) {
            $viewName = "global";
        }
        $this->log = KLogger::instance(LOGPATH . 'ErrorCollector/view/' . $viewName, KLOGGER_ERROR_LEVEL);
    }

    public function getErrorModel() {
        return $this->errors;
    }

    function addErrorMessage($key, $message) {
        if (isset($this->errors[$key])) {
            $this->errors[$key] .= $message;
        } else {
            $this->errors[$key] = $message;
        }
    }

}

?>
