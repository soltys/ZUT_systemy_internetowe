<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
class Html {
    public static function createNavigationLink($viewName, $linkName, $rights) {
        $auth = Authentication::getInstance();
        if ($auth->isUserHaveRights($rights)) {
            echo "<a href=\"index.php?view=$viewName\"><li class=\"menuItem\">$linkName</li></a>";
        }
    }

    public static function createSelectedOption($number, $selectedValue)
    {
        echo "<option ";
        if ($number == $selectedValue) {
            echo "selected=\"selected\"";
        }
            echo " >$number</option>";
    }
}

?>
