<?php
require_once 'config.php';
require_once ABSPATH . 'TemplateEngine.php';
require_once ABSPATH . 'klogger/klogger.php';

class Controller {

    private $page;
    private $log;

    public function __construct() {
        $this->log = KLogger::instance(ABSPATH . '/logs/Controller', KLOGGER_ERROR_LEVEL);
    }

    private function loadSegment($viewName, $segmentName) {
        $segmentFileName = "views/" . $viewName . "/" . $segmentName . ".tpl.php";
        $this->log->logInfo("loading " . $segmentName . " from", $segmentFileName);
        $segmentTemplate = new TemplateEngine($segmentFileName);

        $this->page->set($segmentName, $segmentTemplate->parse());
    }

    private function isViewExists($viewName) {
        return is_dir("views/" . $viewName);
    }

    public function getView($viewName) {
        if (! $this->isViewExists($viewName)) {
            $this->log->logError("View '". $viewName . "' do not exists");
            $viewName = "notFound";
        }
        $this->page = new TemplateEngine("views/shared.tpl.php");
        $this->log->logInfo("loading shared template");

        $this->loadSegment($viewName, "body");
        $this->loadSegment($viewName, "sidebar");

        return $this->page->parse();
    }

}

?>
