<?php

require_once 'TemplateEngine.php';
require_once 'klogger/klogger.php';

class Controller {

    private $page;
    private $log;

    public function __construct() {
        $this->log = KLogger::instance(dirname(__FILE__) . '/logs', KLogger::DEBUG);
    }

    private function loadSegment($viewName, $segmentName) {
        $segmentFileName = "views/" . $viewName . "/" . $segmentName . ".tpl.php";
        $this->log->logInfo("loading" . $segmentName . " from", $segmentFileName);
        $segmentTemplate = new TemplateEngine($segmentFileName);

        $this->page->set($segmentName, $segmentTemplate->parse());
    }

    private function isViewExists($viewName) {
        return is_dir("views/" . $viewName);
    }

    public function getView($viewName) {
        if (! $this->isViewExists($viewName)) {
            $this->log->logError("View '". $viewName . "' do not exists");
            $viewName = "notfound";
        }
        $this->page = new TemplateEngine("views/shared.tpl.php");
        $this->log->logInfo("loading shared template");

        $this->loadSegment($viewName, "body");
        $this->loadSegment($viewName, "sidebar");

        return $this->page->parse();
    }

}

?>
