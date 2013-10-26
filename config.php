<?php
if (file_exists('config.private.php')) {
  include_once 'config.private.php';
} else {
    echo "File config.private.php is not present";
	exit();
}



define('ABSPATH',  dirname(__FILE__).'/');
define('LOGPATH', ABSPATH . "logs/");
define('KLOGGER_ERROR_LEVEL', 7);


/*
 * SAMPLE CONFIG.PRIVATE.PHP

    define('DB_NAME', '');
    define('DB_USER', '');
    define('DB_PASSWORD', '');
    define('DB_HOST', '');
    define('DB_CHARSET', '');
 *
 */
?>
