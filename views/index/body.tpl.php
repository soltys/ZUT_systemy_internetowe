<?php
require_once ABSPATH . 'common/Authentication.php';
$auth = Authentication::getInstance();
?>
<h1>To jest strona główna</h1>
<?php
if ($auth->isUserLoggedIn()) {
    echo "<p>Witaj {$auth->getUserFirstName()}</p>";
}
?>
