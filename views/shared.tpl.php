<?php
require_once 'config.php';
require_once ABSPATH . 'DAL/SessionManager.php';
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

        <div id="header">
            <h1>Header</h1>
        </div>
        <div class="colmask holygrail">
            <div class="colmid">
                <div class="colleft">
                    <div class="col1wrap">
                        <div class="col1">
                            <?php print $this->body; ?>
                        </div>
                    </div>
                    <div class="col2">

                        <ul class="nav">
                            <li><a href="index.php">Strona główna</a></li>
                            <li><a href="index.php?view=form" >Formularz</a></li>
                            <li><a href="index.php?view=currentSession" >Podgląd sesji</a></li>
                            <li><a href="index.php?view=database">Baza danych</a></li>
                            <li><a href="index.php?view=editPerson">Edytuj dane pracownika</a></li>
                            <li><a href="index.php?view=deletePerson">Usuń pracownika</a></li>
                        </ul>

                    </div>
                    <div class="col3">
                        <?php
                        if ($this->sidebar) {
                            print $this->sidebar;
                        } else {
                            ?>

                            <ul>
                                <li><a href="http://google.com">google</a></li>
                                <li><a href="http://onet.pl">Onet</a></li>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
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

    </body>
</html>
