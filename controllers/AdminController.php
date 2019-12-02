<?php

namespace Controllers;


class AdminController {

    public function __construct() {
        if(!admin()) abort(404);
    }

    public function panel() {
        include_once ROOT . '/view/admin/panel.php';
    }

}

