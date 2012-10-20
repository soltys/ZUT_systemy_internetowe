<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paginator
 *
 * @author Soltys
 */
class Paginator {

    private $rowsPerPage;
    private $data;

    function __construct($data, $rowsPerPage = 10) {
        $this->rowsPerPage = $rowsPerPage;
        $this->data = $data;
    }

    public function paginate($page) {

        return array_slice($this->data, ($page - 1) * $this->rowsPerPage, $this->rowsPerPage);
    }

    private function getTotalPages() {
        return count($this->data) / $this->rowsPerPage;
    }

    public static function getPage() {
        if (isset($_GET)) {
            if (isset($_GET["page"])) {
                $page = $_GET["page"];
            } else {
                $page = 1;
            }
        } else {
            $page = 1;
        }
        return $page;
    }

    public function paginationNavigation($page) {
        $totalPages =  $this->getTotalPages();
        if ($page > 1) {
            $prev = $page - 1;
            print "<a href=\"index.php?view=database&page=$prev\">&lt;</a>";
        }
        for ($i = 1; $i < $totalPages + 1; $i++) {
            print "<a href=\"index.php?view=database&page=$i\">$i</a>";
        }
        if ($page < $totalPages) {
            $next = $page + 1;
            print "<a href=\"index.php?view=database&page=$next\">&gt;</a>";
        }
    }

}

?>
