<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . 'common/TemplateEngine.php';
require_once ABSPATH . 'klogger/klogger.php';
require_once ABSPATH . 'common/Authentication.php';
define('VIEW_404', '404');

class Controller {

    private $page;
    private $log;
    private $publicViews;
    private $auth;

    public function __construct() {
        $this->log = KLogger::instance(ABSPATH . '/logs/Controller', KLOGGER_ERROR_LEVEL);
        $this->auth = Authentication::getInstance();
        $this->publicViews = array("index", "login", "logout", "register", VIEW_404);
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
            $viewName = VIEW_404;
        }

        if (!in_array($viewName, $this->publicViews)) {
            if (!$this->auth->isUserLoggedIn()) {
                header("Location: index.php?view=login");
            }
        }
        $this->page = new TemplateEngine("views/shared.tpl.php");
        $this->log->logInfo("loading shared template");

        $this->loadSegment($viewName, "body");
        $this->loadSegment($viewName, "sidebar");

        return $this->page->parse();
    }

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

    public static function createNavigationLink($viewName, $linkName, $rights) {
        $auth = Authentication::getInstance();
        if ($auth->isUserHaveRights($rights)) {
            echo "<li><a href=\"index.php?view=$viewName\">$linkName</a></li>";
        }
    }

}

?>
