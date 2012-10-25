<?php

require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/SessionManager.php';
require_once ABSPATH . 'common/Paginator.php';
require_once ABSPATH . 'model/Person.php';
$sessionManager = SessionManager::getInstance();
$people = $sessionManager->getPeople();
$paginator = new Paginator($people);
$page = Paginator::getPage();
$pagePeople = $paginator->paginate($page);
?>

<h2>Obecna sesja</h2>


<table  border="1">
    <tr>
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
        ?>
        <tr>
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