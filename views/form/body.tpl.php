<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/SessionManager.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/Person.php';
require_once ABSPATH . 'klogger/klogger.php';
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

    $firstName = getPostData("firstName");
    $lastName = getPostData("lastName");
    $gender = getPostData("gender");
    $maidenName = getPostData("maidenName");
    $email = getPostData("email");
    $postalCode = getPostData("postalCode");
    ?>
    <form method="post" action="index.php?view=form&action=addPerson">
        <table border="0">
            <tbody>
                <tr>
                    <td><label >Imię</label></td>
                    <td><input   type="text" name="firstName" <?php echo 'value="'.$firstName.'"'; ?>/></td>
                    <td><?php getErrorMessage($modelErrors, "firstName") ?></td>
                </tr>
                <tr>
                    <td><label >Nazwisko</label></td>
                    <td><input  type="text" name="lastName" <?php echo 'value="'.$lastName.'"'; ?>/></td>
                    <td><?php getErrorMessage($modelErrors, "lastName") ?></td>
                </tr>
                <tr>
                    <td>Płeć</td>
                    <td><input  type="radio" name="gender" value="women" <?php if($gender=="women") echo ' checked="checked" ';?>/> <label>Kobieta</label></td>
                    <td><?php getErrorMessage($modelErrors, "gender") ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input   type="radio" name="gender" value="men" <?php if($gender=="men") echo ' checked="checked" ';?>/><label>Mężczyzna</label></td>
                    <td></td>
                </tr>
                <tr>
                    <td> <label>Nazwisko panieńskie</label></td>
                    <td><input  type="text" name="maidenName" <?php echo 'value="'.$maidenName.'"'; ?>/></td>
                    <td><?php getErrorMessage($modelErrors, "maidenName") ?></td>
                </tr>
                <tr>
                    <td><label>Email</label></td>
                    <td><input  type="text" name="email" <?php echo 'value="'.$email.'"'; ?>/></td>
                    <td><?php getErrorMessage($modelErrors, "email") ?></td>
                </tr>
                <tr>
                    <td><label>Kod pocztowy</label></td>
                    <td><input  type="text" name="postalCode" <?php echo 'value="'.$postalCode.'"'; ?>/></td>
                    <td><?php getErrorMessage($modelErrors, "postalCode") ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Dodaj nowego pracownika"/></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php
}

if (isset($_GET['action'])) {
    $actionName = $_GET['action'];
    $errorCollector = new ErrorCollector("userInfo");
    if ($actionName == "addPerson") {

        $firstName = getPostData("firstName");
        if ($firstName == NULL) {
            $errorCollector->addErrorMessage("firstName", "Nie wprowadzono imienia\n");
        }

        $lastName = getPostData("lastName");
        if ($lastName == NULL) {
            $errorCollector->addErrorMessage("lastName", "Nie wprowadzono nazwiska\n");
        }

        $gender = getPostData("gender");
        if ($gender == NULL) {
            $errorCollector->addErrorMessage("gender", "Nie wprowadzono płci\n");
        }

        $maidenName = getPostData("maidenName");
        if ($maidenName == NULL) {
            $errorCollector->addErrorMessage("maidenName", "Nie wprowadzono nazwiska panieńskiego\n");
        }

        $email = getPostData("email");
        if ($email == NULL) {
            $errorCollector->addErrorMessage("email", "Nie wprowadzono adresu email\n");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorCollector->addErrorMessage("email", "Email jest niepoprawny\n");
        }

        $postalCode = getPostData("postalCode");
        if ($postalCode == NULL) {
            $errorCollector->addErrorMessage("postalCode", "Nie wprowadzono kodu pocztowego\n");
        }
        if (!preg_match('/^\d{2}-\d{3}$/', $postalCode)) {
            $errorCollector->addErrorMessage("postalCode", "Kod pocztowy jest niepoprawny\n");
        }

        if (count($errorCollector->getErrorModel()) > 0) {
            displayForm($errorCollector->getErrorModel());
        } else {
            $firstName = getPostData("firstName");
            $lastName = getPostData("lastName");
            $gender = getPostData("gender");
            $maidenName = getPostData("maidenName");
            $email = getPostData("email");
            $postalCode = getPostData("postalCode");
            $person = new Person($firstName, $lastName, $gender, $maidenName, $email, $postalCode);
            $sessionManager = SessionManager::getInstance();
            $sessionManager->addPerson($person);
            $database = new Database();
            $database->addPerson($person);
            Controller::gotoView("database");
        }
    }
    if ($actionName == "success") {
        echo "<h2>Zakutalizowano dane</h2>";
    }
} else {
    displayForm();
}
?>