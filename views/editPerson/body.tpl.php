<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/Person.php';
require_once ABSPATH . 'common/Paginator.php';

$errorCount = 0;
$log = KLogger::instance(LOGPATH . 'editPerson', KLOGGER_ERROR_LEVEL);
function getPostData($name) {
    $log = KLogger::instance(ABSPATH . 'logs/editPerson', KLOGGER_ERROR_LEVEL);
    global $errorCount;
    if (isset($_POST[$name])) {
        if (empty($_POST[$name])) {
            $log->logNotice("$name is empty");
            $errorCount++;
        }
        return $_POST[$name];
    } else {
        $log->logNotice("$name is not set");
        $errorCount++;
        return NULL;
    }
}

global $errorCount;
if (isset($_GET['personId'])) {
    $editPersonId = $_GET['personId'];
}
if (isset($_GET['confirm'])) {
    $confirmUpdate = true;
}

$db = new Database();
$people = $db->getPeople();
$paginator = new Paginator($people);
$page = Paginator::getPage();
$pagePeople = $paginator->paginate($page);
?>

<h2>Edytuj pracownika</h2>
<?php
if (isset($editPersonId)) {
    if (isset($confirmUpdate)) {
        $firstName = getPostData("firstName");
        $lastName = getPostData("lastName");
        $gender = getPostData("gender");
        $maidenName = getPostData("maidenName");
        $email = getPostData("email");
        $postalCode = getPostData("postalCode");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $log->logNotice("Email: $email is invalid");
            $errorCount++;
        }
        if (!preg_match('|\d{2}-\d{3}|', $postalCode)) {
            $log->logNotice("Postal code: $postalCode is invalid");
            $errorCount++;
        }
        if ($errorCount > 0) {
            print"<p>Liczba błędów w formularzu: $errorCount </p>";
        } else {
            $person = new Person($firstName, $lastName, $gender, $maidenName, $email, $postalCode, $editPersonId);
            $db->updatePerson($person);            
            Controller::gotoView("database");
        }
    } else {
        $person = $db->getPerson($editPersonId);
        ?>
        <form method="post" action="<?php print "index.php?view=editPerson&personId=$editPersonId&confirm=yes" ?>">
            <table border="0">
                <tbody>
                    <tr>
                        <td><label >Imię</label></td>
                        <td><input  type="text" name="firstName" <?php print "value=\"{$person->getFirstName()}\""; ?>/></td>
                    </tr>
                    <tr>
                        <td><label >Nazwisko</label></td>
                        <td><input  type="text" name="lastName"  <?php print "value=\"{$person->getLastName()}\""; ?>/></td>
                    </tr>
                    <tr>
                        <td>Płeć</td>
                        <td><input  type="radio" name="gender" value="women"
                            <?php
                            if ($person->getGender(true) == "woman") {
                                print " checked=\"checked\"";
                            }
                            ?> /> <label>Kobieta</label></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input   type="radio" name="gender" value="men"
                            <?php
                            if ($person->getGender(true) == "men") {
                                print " checked=\"checked\"";
                            }
                            ?> /><label>Mężczyzna</label></td>
                    </tr>
                    <tr>
                        <td> <label>Nazwisko panieńskie</label></td>
                        <td><input  type="text" name="maidenName"  <?php print "value=\"{$person->getMaidenName()}\""; ?> /></td>
                    </tr>
                    <tr>
                        <td><label>Email</label></td>
                        <td><input  type="text" name="email"  <?php print "value=\"{$person->getEmail()}\""; ?>/></td>
                    </tr>
                    <tr>
                        <td><label>Kod pocztowy</label></td>
                        <td><input  type="text" name="postalCode"  <?php print "value=\"{$person->getPostalCode()}\""; ?>/></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Potwierdź zmiany"/></td>
                        <td><a href="index.php?view=database"><button>Odrzuć zmiany</button></a></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
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