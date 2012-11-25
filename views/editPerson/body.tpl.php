<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/Person.php';
require_once ABSPATH . 'common/Paginator.php';
require_once ABSPATH . 'klogger/klogger.php';
require_once ABSPATH . "common/ErrorCollector.php";


$db = new Database();
$people = $db->getPeople();
$paginator = new Paginator($people);
$page = Paginator::getPage();
$pagePeople = $paginator->paginate($page);

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

if (isset($_GET['personId'])) {
    $editPersonId = $_GET['personId'];
}
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'updatePerson') {
$confirmUpdate = true;
    }
}
/**
 *
 * @param Person $person
 * @param ErrorCollector $modelErrors
 */
function displayForm($person =null, $modelErrors = array()) {

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

    $firstName = $person->getFirstName();
    $lastName = $person->getLastName();
    $gender = $person->getGender(true);
    $maidenName = $person->getMaidenName();
    $email = $person->getEmail();
    $postalCode = $person->getPostalCode();
    echo $gender;
    ?>
<form method="post" action="index.php?view=editPerson&personId=<?php echo $person->getPersonId(); ?>&action=updatePerson">
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
                    <td>Płeć </td>
                    <td><input  type="radio" name="gender" value="women" <?php if($gender=="women") echo ' checked="checked" ';?>/><label>Kobieta</label></td>
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



?>

<h2>Edytuj pracownika</h2>
<?php
if (isset($editPersonId)) {
    if (isset($confirmUpdate)) {
        $errorCollector = new ErrorCollector("editPerson");
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
            $person = $db->getPerson($editPersonId);
            displayForm($person, $errorCollector->getErrorModel());
        } else {
            $firstName = getPostData("firstName");
            $lastName = getPostData("lastName");
            $gender = getPostData("gender");
            $maidenName = getPostData("maidenName");
            $email = getPostData("email");
            $postalCode = getPostData("postalCode");
            $person = new Person($firstName, $lastName, $gender, $maidenName, $email, $postalCode,$editPersonId);
            $database = new Database();
            $database->updatePerson($person);
            Controller::gotoView("editPerson");
        }
    }
    else
    {
        $person = $db->getPerson($editPersonId);
        displayForm($person);
    }
} else {
    ?>
    <table  border="1">
        <tr>
            <th>Akcja</th>
            <th>ID</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Płeć</th>
            <th>Naz. pan</th>
            <th>Email</th>
            <th>Kod</th>
        </tr>

        <?php
        foreach ($pagePeople as $person) {
            $personId = $person->getPersonId();
            ?>
            <tr>
                <td><?php print "<a href=\"index.php?view=editPerson&personId=$personId\">Edytuj</a>" ?> </td>
                <td><?php print $person->getPersonId(); ?></td>
                <td><?php print $person->getFirstName(); ?></td>
                <td><?php print $person->getLastName(); ?></td>
                <td><?php print $person->getGender(); ?></td>
                <td><?php print $person->getMaidenName(); ?></td>
                <td><?php print $person->getEmail(); ?></td>
                <td><?php print $person->getPostalCode(); ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <p>
        <?php
        $paginator->paginationNavigation($page);
        ?>
    </p>
    <?php
}
?>