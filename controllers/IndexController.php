<?php

namespace Controllers;

require_once ROOT . '/db/models/Task.php';
require_once ROOT . '/helpers/Pager.php';
use Db\Models\Task;
use Helpers\Pager;

class IndexController {

    /**
     * Main page of the application.
     * @param int $page - chunk number of data to display
     */
    public function index($page = 1) {

        $lifo = true;
        $sort_by = 'id';

        if(isset($_SESSION['sort_criteria'])) {
            $lifo = $_SESSION['sort_criteria']['order'] == 'descending' ? true : false;
            $sort_by = trim($_SESSION['sort_criteria']['sort_by']);
        }

        $p = new Pager(new Task(), 3, $lifo, $sort_by);
        if(!$pager_list = $p->feed($page)) abort(404);
        $pager = $pager_list['pager'];
        $items = $pager_list['result_set'];

        require_once ROOT . '/view/main/index.php';
    }

    /**
     * Criteria for Main Page feed line.
     */
    public function sort_criteria() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        $_SESSION['sort_criteria']['order'] = $_POST['order'];
        $_SESSION['sort_criteria']['sort_by'] = $_POST['sort_by'];
        header('Location: /');
        return;
    }

}
