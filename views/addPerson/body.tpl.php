<?php
require_once dirname(__FILE__) . '/../../DAL/SessionManager.php';
require_once dirname(__FILE__) . '/../../model/Person.php';
require_once dirname(__FILE__) . '/../../klogger/klogger.php';
$errorCount = 0;

function getPostData($name) {
    $log = KLogger::instance(dirname(__FILE__) . '/../../logs/addPerson');
    global $errorCount;
    if (isset($_POST[$name])) {
        if (empty($_POST[$name])) {
            $log->logNotice("$name is empty");

            $errorCount++;
        }
        return $_POST[$name];
    } else {
        $log->logNotice("$name is not set empty");
        $errorCount++;
        return NULL;
    }
}

global $errorCount;
$log = KLogger::instance(dirname(__FILE__) . '/../../logs/addPerson');
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
?>
<?php
if ($errorCount == 0) {
    ?>
    <p>Imię: <?php print $firstName; ?></p>
    <p>Nazwisko: <?php print $lastName; ?></p>
    <p>Płeć: <?php print $gender; ?></p>
    <p>Email: <?php print $email; ?></p>
    <p>Kod pocztowy: <?php print $postalCode; ?></p>

    <?php
    $person = new Person($firstName, $lastName, $gender, $maidenName, $email, $postalCode);
    $sessionManager = SessionManager::getInstance();
    $sessionManager->addPerson($person);
} else {
    ?>
    <p>Liczba błędów w formularzu:  <?php print $errorCount ?></p>
    <?php
}
?>


