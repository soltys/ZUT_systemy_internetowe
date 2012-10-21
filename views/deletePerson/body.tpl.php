<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/Person.php';
require_once ABSPATH . 'common/Paginator.php';

if (isset($_GET['personId'])) {
    $deletePersonId = $_GET['personId'];
}
if (isset($_GET['confirm'])) {
    $confirmDelete = true;
}


$db = new Database();
$people = $db->getPeople();
$paginator = new Paginator($people);
$page = Paginator::getPage();
$pagePeople = $paginator->paginate($page);
?>

<h2>Usuń pracownika</h2>

<?php
if (isset($deletePersonId)) {
    if (isset($confirmDelete)) {
        $db->deletePerson($deletePersonId);
        header("Location: index.php?view=deletePerson");
    } else {
        print "<p>Czy chcesz usunąć ten rekord? </p>";
        print "<a href=\"index.php?view=deletePerson&personId=$deletePersonId&confirm=yes\"><button>Tak</button></a>";
        print "<a href=\"index.php?view=deletePerson\"><button>Nie</button></a>";
    }
    ?>
    <?php
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
                <td><?php print "<a href=\"index.php?view=deletePerson&personId=$personId\">Usuń</a>" ?> </td>
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