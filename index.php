<?php

require 'Controller.php';
if (!empty($_GET)) {
    $viewName = $_GET["view"];
}
if (!isset($viewName)) {
    $viewName = "index";
}

$controller = new Controller();
print $controller->getView($viewName)
?>
