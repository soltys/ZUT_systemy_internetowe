<?php

require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/Person.php';
$db = new Database();
$people = $db->getPeople();
?>

<p>Baza danych</p>

<?php

foreach ($people as $person) {
    ?>

  <p>Imię: <?php print $person->getFirstName(); ?></p>
    <p>Nazwisko: <?php print $person->getLastName(); ?></p>
    <p>Płeć: <?php print $person->getGender(); ?></p>
    <p>Email: <?php print $person->getEmail(); ?></p>
    <p>Kod pocztowy: <?php print $person->getPostalCode(); ?></p>
<hr/>
    <?php
}
?>