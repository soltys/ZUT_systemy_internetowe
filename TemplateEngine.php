<?php

require_once 'klogger/klogger.php';

class TemplateEngine {

    private $template;

    function __construct($template = null) {
        if (isset($template)) {
            $this->load($template);
        }
    }

    public function set($var, $content) {
        $this->$var = $content;
    }

    public function load($template) {

        if (!is_file($template)) {
            $log = KLogger::instance(dirname(__FILE__) . '/logs', KLogger::DEBUG);
            $log->logNotice("File not found: $template");
        } elseif (!is_readable($template)) {
            throw new IOException("Could not access file: $template");
        } else {
            $this->template = $template;
        }
    }

    public function parse() {
        if (!is_file( $this->template)) {
            return "";
        }
        ob_start();
        require $this->template;
        $content = ob_get_clean();
        return $content;
    }

}

?>
