<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'common/Authentication.php';
require_once ABSPATH . 'model/User.php';
require_once ABSPATH . 'klogger/klogger.php';
$log = KLogger::instance(LOGPATH . 'login', KLOGGER_ERROR_LEVEL);
$auth = Authentication::getInstance();

if (isset($_GET['tryLogin'])) {
    if (isset($_POST["login"])) {
        $login = $_POST["login"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    if (isset($login) && isset($password) && !empty($login) && !empty($password)) {
        if ($auth->checkCredentials($login, $password)) {
            $auth->login($login);
            Controller::gotoView("index");
        } else {
            Controller::gotoView("login", "wrongCredentials");
        }
    } else {
        Controller::gotoView("login", "wrongCredentials");
    }
}
?>
<h1>Logowanie</h1>
<?php
if (isset($_GET['wrongCredentials'])) {
    echo "<h2> Błędne dane do logowania</h2>";
}
?>
<form action="index.php?view=login&tryLogin=yes" method="post">
    <table border=0>
        <tbody>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login"/></td>
            </tr>
            <tr>
                <td>Hasło</td>
                <td><input type="password" name="password"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="zaloguj!"/></td>
            </tr>
        </tbody>
    </table>
</form>