<?php
require_once 'config.php';
require_once ABSPATH . 'DAL/SessionManager.php';
require_once ABSPATH . 'common/Authentication.php';
require_once ABSPATH . "common/Html.php";
$auth = Authentication::getInstance();
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>Paweł Sołtys Sołtysiak</title>

        <link rel="stylesheet" type="text/css" href="styles.css" />
        <!--[if lt IE 7]>
<style media="screen" type="text/css">
.col1 {
        width:100%;
    }
</style>
<![endif]-->
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1>-_SYSTEMY INTERNETOWE_-</h1>
            </div>
            <div id="left_sidebar">
                <ul class="nav">
                    <?php
                    Html::createNavigationLink("index", "Strona główna", 0);
                    Html::createNavigationLink("currentSession", "Podgląd sesji", 1);
                    Html::createNavigationLink("form", "Formularz", 1);
                    Html::createNavigationLink("database", "Baza danych", 1);
                    Html::createNavigationLink("editPerson", "Edytuj dane pracownika", 2);
                    Html::createNavigationLink("deletePerson", "Usuń pracownika", 3);
                    Html::createNavigationLink("userInfo", "Zmień dane", 1);
                    Html::createNavigationLink("changeUserRights", "Zmień poziom dostępu", 4);
                    Html::createNavigationLink("deleteUser", "Usuń użytkownika", 4);
                    ?>

                </ul>

            </div>
            <div id="content">

                <?php print $this->body; ?>
            </div>

            <div id="right_sidebar">

                <?php
                print $this->sidebar;
                if (!$auth->isUserLoggedIn()) {
                    ?>
                    <ul class="nav">
                        <li class="menuItem"><a href="index.php?view=login">Login</a></li>
                        <li class="menuItem"><a href="index.php?view=register">Rejestracja</a></li>
                    </ul>
                    <?php
                } else {
                    ?>
                    <ul class="nav">
                        <li class="menuItem"><a href="index.php?view=logout">Wyloguj</a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>

            <div id="footer">
                <p>Paweł 'sołtys' Sołtysiak I1-32</p>
                <?php
                $sessionManager = SessionManager::getInstance();
                $peopleCount = $sessionManager->getPeopleCount();
                if ($peopleCount > 0) {
                    print "<p>Liczba dodanych pracowników $peopleCount w tej sesji</p>";
                }
                ?>
            </div>
        </div>
    </body>
</html>
