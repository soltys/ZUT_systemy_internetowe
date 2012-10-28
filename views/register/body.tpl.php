<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'model/User.php';
require_once ABSPATH . "common/Authentication.php";


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

function addErrorMessage(&$modelErrors, $key, $message) {
    if (isset($modelErrors[$key])) {
        $modelErrors[$key] .= $message;
    } else {
        $modelErrors[$key] = $message;
    }
}

$auth = Authentication::getInstance();
if (isset($_GET['action'])) {
    $actionName = $_GET['action'];
    $modelErrors = array();
    if ($actionName == "register") {
        $login = getPostData("login");
        if ($login == NULL) {
            addErrorMessage($modelErrors, "login", "Nie wprowadzono loginu\n");
        }
        if (strlen($login) < 6) {
            addErrorMessage($modelErrors, "password", "Login krótszy niż 6 znaków\n");
        }
        if ($auth->isLoginExits($login)) {
            addErrorMessage($modelErrors, "login", "Taki login już istnieje\n");
        }

        $password = getPostData("password");
        if ($password == NULL) {
            addErrorMessage($modelErrors, "password", "Nie wprowadzono hasła\n");
        }
        if (strlen($password) < 6) {
            addErrorMessage($modelErrors, "password", "Hasło krótsze niż 6 znaków\n");
        }

        $passwordAgain = getPostData("passwordAgain");
        if ($passwordAgain == NULL) {
            addErrorMessage($modelErrors, "passwordAgain", "Nie wprowadzono ponownie hasła\n");
        }
        if ($password != $passwordAgain) {
            addErrorMessage($modelErrors, "passwordAgain", "Hasła się nie zgadzają\n");
        }

        if (count($modelErrors) > 0) {
            displayForm($modelErrors);
        } else {
            $firstName = getPostData("firstName");
            $lastName = getPostData("firstName");
            $rights = 0;

            $user = new User($login, $password, $rights, $firstName, $lastName);
            $auth->addUser($user);
        }
    }
} else {
    displayForm();
}

function displayForm($modelErrors = array()) {

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
    <h1>Rejestracja</h1>

    <form action="index.php?view=register&action=register" method="post">
        <table border=0>
            <tbody>
                <tr>
                    <td>Login </td>
                    <td><input type="text" name="login"/></td>
                    <td><?php getErrorMessage($modelErrors, "login") ?></td> 
                </tr>
                <tr>
                    <td>Hasło </td>
                    <td><input type="password" name="password"/></td>
                    <td> <?php getErrorMessage($modelErrors, "password") ?> </td>
                </tr>
                <tr>
                    <td>Powtórz hasło </td>
                    <td><input type="password" name="passwordAgain"/></td>
                    <td> <?php getErrorMessage($modelErrors, "passwordAgain") ?> </td>
                </tr>
                <tr>
                    <td>Imię </td>
                    <td><input type="text" name="firstName"</td>
                    <td> <?php getErrorMessage($modelErrors, "fistName") ?> </td>
                </tr>
                <tr>
                    <td>Nazwisko </td>
                    <td><input type="text" name="lastName"/></td>
                    <td> <?php getErrorMessage($modelErrors, "lastName") ?> </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Rejestruj"/></td>
                </tr>
            </tbody>
        </table>
    </form>
<?php } ?>