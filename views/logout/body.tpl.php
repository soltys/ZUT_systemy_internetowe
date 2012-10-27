<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . "common/Authentication.php";
$auth = Authentication::getInstance();
$auth->logout();
Controller::gotoView("index");

?>
