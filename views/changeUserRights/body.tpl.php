<?php
require_once dirname(dirname(__FILE__)) . '/config.php';
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'model/User.php';
require_once ABSPATH . 'common/Paginator.php';
if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];
}

if (isset($_POST['newRights'])) {
    $newRights = $_POST['newRights'];
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($userId) && isset($action)) {
    if ($action == "updateRights") {
        echo "<h3> Zaktualizowano prawa dostępu</h3>";
        $auth = Authentication::getInstance();
        $auth->updateUserRights($userId, $newRights);
    }
}

$db = new Database();
$users = $db->getUsers();
$paginator = new Paginator($users);
$page = Paginator::getPage();
$pageUsers = $paginator->paginate($page);
?>

<h2>Zmień prawa dostępu</h2>

<table  border="1">
    <tr>

        <th>Login</th>
        <th>Imię</th>
        <th>Nazwisko</th>
        <th>Poziom</th>
        <th>Potwierdź</th>
    </tr>
    <?php
    foreach ($pageUsers as $user) {

        $userId = $user->getUserId();
        ?>
        <tr>

            <td><?php print $user->getLogin(); ?></td>
            <td><?php print $user->getFirstName(); ?></td>
            <td><?php print $user->getLastName(); ?></td>
            <form action="index.php?view=changeUserRights&action=updateRights" method="POST">
            <td>
                <select name="newRights">
                    <?php
                    for ($i = 0; $i <= 4; $i++) {
                        Html::createSelectedOption($i, $user->getRights());
                    }
                    ?>
                </select>

            </td>
            <input type="hidden" name="userId" value="<?php echo $userId;?>"/>
            <td><input type="submit" value="Potwierdź"/></td>
            </form>
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

