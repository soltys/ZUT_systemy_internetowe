<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/Person.php';
require_once ABSPATH . 'common/Paginator.php';


if (isset($_GET['personId'])) {
    $editPersonId = $_GET['personId'];
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
print "eskimos";
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