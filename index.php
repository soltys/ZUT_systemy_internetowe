<?php

require_once 'Controller.php';
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
