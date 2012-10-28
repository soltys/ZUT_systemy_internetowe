<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
class Html {
    public static function createNavigationLink($viewName, $linkName, $rights) {
        $auth = Authentication::getInstance();
        if ($auth->isUserHaveRights($rights)) {
            echo "<li><a href=\"index.php?view=$viewName\">$linkName</a></li>";
        }
    }
}

?>
