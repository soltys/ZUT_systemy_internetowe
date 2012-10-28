<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . 'common/TemplateEngine.php';
require_once ABSPATH . 'klogger/klogger.php';
require_once ABSPATH . 'common/Authentication.php';
define('VIEW_404', '404');
define('VIEW_403', '403');

class Controller {

    private $page;
    private $log;
    private $viewRights;
    private $auth;

    public function __construct() {
        $this->log = KLogger::instance(LOGPATH . 'Controller', KLOGGER_ERROR_LEVEL);
        $this->auth = Authentication::getInstance();
        $level1 = array("form", "currentSession", "database", "userInfo");
        $level2 = array("editPerson");
        $level3 = array("deletePerson");
        $level4 = array("editUser", "deleteUser");
        $this->viewRights = array(1 => $level1, 2 => $level2, 3 => $level3, 4 => $level4);
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
        if (!$this->isViewExists($viewName)) {
            $this->log->logError("View '" . $viewName . "' do not exists");
            Controller::gotoView(VIEW_404);
        }

        if (!$this->auth->isUserHaveRights($this->getViewRights($viewName))) {
            $this->log->logError("User tried to reach '" . $viewName . "' but user is unauthorized ");
            Controller::gotoView(VIEW_403);
        }

        $this->page = new TemplateEngine("views/shared.tpl.php");
        $this->log->logInfo("loading shared template");

        $this->loadSegment($viewName, "body");
        $this->loadSegment($viewName, "sidebar");

        return $this->page->parse();
    }

    private function getViewRights($viewName) {
        foreach ($this->viewRights as $key => $viewNames) {
            foreach ($viewNames as $name) {
                if ($viewName == $name) {
                    return $key;
                }
            }
        }
        return 0;
    }

// *** STATIC FUNCTIONS ***
    public static function getCurrentView() {
        if (isset($_GET["view"])) {
            return $_GET["view"];
        } else {
            return "index";
        }
    }

    public static function gotoView($viewName, $params = NULL) {
        $header = "Location: index.php?view=$viewName";
        if (isset($params)) {
            $header .= "&" . $params;
        }
        header($header);
    }

   

}

?>
