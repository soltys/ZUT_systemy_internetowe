<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . 'klogger/klogger.php';

class ErrorModel {

    private $errorModel;
    private $log;

    function __construct($viewName = NULL) {
        $this->errorModel = array();

        if (!isset($viewName)) {
            $viewName = "global";
        }
        $this->log = KLogger::instance(LOGPATH . 'ErrorModel/view' . $viewName, KLOGGER_ERROR_LEVEL);
    }

    public function getErrorModel() {
        return $this->errorModel;
    }

    function addErrorMessage($key, $message) {
        if (isset($this->modelErrors[$key])) {
            $this->modelErrors[$key] .= $message;
        } else {
            $this->modelErrors[$key] = $message;
        }
    }

}

?>
