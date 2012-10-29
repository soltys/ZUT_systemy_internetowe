<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/User.php';
require_once ABSPATH . 'common/Paginator.php';

if (isset($_GET['userId'])) {
    $deleteUserId = $_GET['userId'];
}
if (isset($_GET['confirm'])) {
    $confirmDelete = true;
}


$db = new Database();
$users = $db->getUsers();
$paginator = new Paginator($users);
$page = Paginator::getPage();
$pageUsers = $paginator->paginate($page);
?>

<h2>Usuń pracownika</h2>

<?php
if (isset($deleteUserId)) {
    if (isset($confirmDelete)) {
        $db->deleteUser($deleteUserId);        
        Controller::gotoView("deleteUser");
    } else {
        print "<p>Czy chcesz usunąć ten rekord? </p>";
        print "<a href=\"index.php?view=deleteUser&userId=$deleteUserId&confirm=yes\"><button>Tak</button></a>";
        print "<a href=\"index.php?view=deleteUser\"><button>Nie</button></a>";
    }
    ?>
    <?php
} else {
    ?>
    <table  border="1">
        <tr>
            <th>Akcja</th>
            <th>Login</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Poziom</th>         
        </tr>
    <?php
    foreach ($pageUsers as $user) {

        $userId = $user->getUserId();
        ?>
            <tr>
                <td><?php print "<a href=\"index.php?view=deleteUser&userId=$userId\">Usuń</a>" ?> </td>                
                <td><?php print $user->getLogin(); ?></td>
                <td><?php print $user->getFirstName(); ?></td>
                <td><?php print $user->getLastName(); ?></td>                
                <td><?php print $user->getRights(); ?></td>
                
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