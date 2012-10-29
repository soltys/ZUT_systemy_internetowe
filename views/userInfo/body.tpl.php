<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'model/User.php';
require_once ABSPATH . "common/Authentication.php";
require_once ABSPATH . "common/ErrorCollector.php";

function getPostData($name) {
    if (isset($_POST[$name])) {
        if (empty($_POST[$name])) {
            return NULL;
        }
        return $_POST[$name];
    } else {
        return NULL;
    }
}

$auth = Authentication::getInstance();

if (isset($_GET['action'])) {
    $actionName = $_GET['action'];
    $errorCollector = new ErrorCollector("userInfo");    
    if ($actionName == "update") {
        $login = getPostData("login");
        $oldUser = $auth->getCurrentUser();
        $oldLogin = $oldUser->getLogin();
        if ($login == NULL) {
            $errorCollector->addErrorMessage("login", "Nie wprowadzono loginu\n");
        }
        if (strlen($login) < 6) {
            $errorCollector->addErrorMessage("password", "Login krótszy niż 6 znaków\n");
        }
        if ($login != $oldLogin && $auth->isLoginExits($login)) {
            $errorCollector->addErrorMessage("login", "Taki login już istnieje\n");
        }

        $password = getPostData("password");
        if ($password == NULL) {
            $errorCollector->addErrorMessage("password", "Nie wprowadzono hasła\n");
        }
        if (strlen($password) < 6) {
            $errorCollector->addErrorMessage("password", "Hasło krótsze niż 6 znaków\n");
        }

        $passwordAgain = getPostData("passwordAgain");
        if ($passwordAgain == NULL) {
            $errorCollector->addErrorMessage("passwordAgain", "Nie wprowadzono ponownie hasła\n");
        }
        if ($password != $passwordAgain) {
            $errorCollector->addErrorMessage("passwordAgain", "Hasła się nie zgadzają\n");
        }

        if (count($errorCollector->getErrorModel()) > 0) {
            displayForm($errorCollector->getErrorModel());
        } else {
            $userId = getPostData("userId");
            $firstName = getPostData("firstName");
            $lastName = getPostData("firstName");

            $user = new User($login, $password, $oldUser->getRights(), $firstName, $lastName, $userId);
            $auth->updateUser($user);
            Controller::gotoView("userInfo","action=success");
        }
    }
    if ($actionName == "success") {
     echo "<h2>Zakutalizowano dane</h2>";
    }
} else {
    displayForm();
}

function displayForm($modelErrors = array()) {
    $auth = Authentication::getInstance();
    $user = $auth->getCurrentUser();

    function getErrorMessage($modelErrors, $key) {
        if (isset($modelErrors[$key])) {
            $messages = explode("\n", $modelErrors[$key]);
            foreach ($messages as $message) {
                print "<p class=\"errorMessage\">$message</p>";
            }
        } else {
            return "";
        }
    }
    ?>
    <h1>Zminana danych użytkownika</h1>

    <form action="index.php?view=userInfo&action=update" method="post">
        <input type="hidden" name="userId" <?php print " value=\"{$user->getUserId()}\" "; ?>/>
        <table border=0>
            <tbody>
                <tr>
                    <td>Login </td>
                    <td><input type="text" name="login" <?php print " value=\"{$user->getLogin()}\" "; ?>/></td>
                    <td><?php getErrorMessage($modelErrors, "login") ?></td> 
                </tr>
                <tr>
                    <td>Hasło </td>
                    <td><input type="password" name="password" /></td>
                    <td> <?php getErrorMessage($modelErrors, "password") ?> </td>
                </tr>
                <tr>
                    <td>Powtórz hasło </td>
                    <td><input type="password" name="passwordAgain"/></td>
                    <td> <?php getErrorMessage($modelErrors, "passwordAgain") ?> </td>
                </tr>
                <tr>
                    <td>Imię </td>
                    <td><input type="text" name="firstName"  <?php print " value=\"{$user->getFirstName()}\" "; ?>/> </td>
                    <td> <?php getErrorMessage($modelErrors, "fistName") ?> </td>
                </tr>
                <tr>
                    <td>Nazwisko </td>
                    <td><input type="text" name="lastName" <?php print " value=\"{$user->getLastName()}\" "; ?>/></td>
                    <td> <?php getErrorMessage($modelErrors, "lastName") ?> </td>
                </tr>
                <tr>
                    <td><input type="submit" value="Zapisz dane"/></td>
                    <td><a href="index.php?view=index"><button type="button">Odrzuć zmiany</button></a></td>
                </tr>
            </tbody>
        </table>
    </form>
<?php } ?>