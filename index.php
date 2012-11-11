<?php
require_once 'config.php';
require_once ABSPATH . 'common/Controller.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (!empty($_GET)) {
    if(isset($_GET["view"]))
    {
        $viewName = $_GET["view"];
    }
}
if (!isset($viewName)) {
    $viewName = "index";
}

$controller = new Controller();
print $controller->getView($viewName)
?>
