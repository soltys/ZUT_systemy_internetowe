<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/Person.php';
require_once ABSPATH . 'common/Paginator.php';
require_once ABSPATH . 'klogger/klogger.php';

$log = KLogger::instance(LOGPATH . 'search', KLOGGER_ERROR_LEVEL);
if (isset($_POST['query'])) {
    $query = $_POST['query'];
} else {
    $log->logError("Query in search was not specifiled!");
}

$db = new Database();
$pieces = explode(" ", $query);
$people = array();
foreach($pieces as $piece)
{
    array_merge($people,$db->searchPeople('lastName', $piece));
}
$paginator = new Paginator($people);
$page = Paginator::getPage();
$pagePeople = $paginator->paginate($page);
?>

<h2>Szukajka</h2>


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