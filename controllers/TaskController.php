<?php

namespace Controllers;


require_once ROOT . '/helpers/Pager.php';
require_once ROOT . '/helpers/Validator.php';
require_once ROOT . '/db/models/Task.php';


use Db\Models\Task;
use Helpers\Auth;
use Helpers\Validator;

class TaskController
{

    /**
     * Browse all resources page.
     */
    public function index() {
        $list = Task::all(false);
        require_once ROOT . '/view/task/index.php';
    }

    /**
     * Resource display page.
     * @param $id - identifier of resource
     */
    public function show($id) {
        if(!$item = Task::find($id)) abort(404);
        require_once ROOT . '/view/task/show.php';
    }

    /**
     * Resource edit form.
     * @param $id resource identifier.
     */
    public function edit($id) {
        unset_old_form_params();
        if(!$item = Task::find( intval($id) )) abort(404);
        require_once ROOT . '/view/task/edit.php';
    }

    /**
     * Persist the "task" in the db table.
     */
    public function store() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $text = $_POST['text'];
        $user_id = auth();

        $errors = false;
        $item = null;

        if($e = Validator::validateLoginEntry($user_name))
            $errors[] = $e;
        if($e = Validator::validateEmail($email))
            $errors[] = $e;
        if ($e = Validator::validateText($text))
            $errors[] = $e;
        if ($errors) {
            $_SESSION['errors'] = $errors;
            header('Location: /');
            return;
        } else {
            $text = $this->textProcessor($text);
            $item = $item ? $item : new Task([$user_name, $email, $text, 0, $user_id]);
        }
        $item->save();
        unset_old_form_params();
        header('Location: /');
        return;
    }

    /**
     * Editing the "task" db record.
     */
    public function update() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!$user = Auth::user()) abort(404);
        $id = $_POST['id'];
        $item = null;
        if(!$item = Task::find($id)) abort(404);
        if(!$this->can_edit($user, $id)) abort(404);

        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $text = $_POST['text'];

        if(admin()) {
            $status = isset($_POST['status']) ? 1 : 0;
        } else {
            $status = $item->status;
        }
        $errors = false;

        if($e = Validator::validateLoginEntry($user_name))
            $errors[] = $e;
        if($e = Validator::validateEmail($email))
            $errors[] = $e;
        if ($e = Validator::validateText($text))
            $errors[] = $e;

        if($errors) {
            include_once ROOT . '/view/task/edit.php';
            return;
        } else {
            $item->user_name = $user_name;
            $item->text = $this->textProcessor($text);
            $item->status = $status;
            $item->save();
            unset_old_form_params();
            redirect('task/' . $item->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        if(!admin()) abort(404);
        $id = $_POST['id'];
        if (!$m = Task::find($id)) abort(404);
        $m->delete();
        unset_old_form_params();
        redirect('/');
    }

    // If it is a task owner
    private function can_edit($user, $id) {
        // is this a task Author or Admin
        if($user->id != $id || $user->role === 'admin')
            return true;
        return false;
    }

    /**
     * Replace malicious software symbols
     * @param $text - text to be checked on code symbols
     * @return string
     */
    private function textProcessor($text) {
        $text = trim($text);
        mb_regex_encoding('UTF-8');
        mb_internal_encoding("UTF-8");
        $a = preg_split('/(?<!^)(?!$)/u', $text);

        for($i = 0; $i < count($a); $i ++) {
            if($a[$i] === '<') $a[$i] = '&lt;';
            if($a[$i] === '>') $a[$i] = '&gt;';
        }
        return implode('', $a);
    }

}
























