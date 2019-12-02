<?php

namespace Controllers;

require_once ROOT . '/db/models/User.php';
require_once ROOT . '/helpers/Validator.php';
require_once ROOT . '/helpers/Auth.php';

use Db\Models\User;
use Helpers\Auth;
use Helpers\Validator;
use Router;


class UserController {

    public function login() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if(Auth::check()) header('Location: /cabinet');
            require_once ROOT . '/view/user/login.php';
            return;
        }
        $login = $_POST['login'];
        $pass = $_POST['password'];
        $errors = false;
        if($e = Validator::validateLoginEntry($login))
            $errors[] = $e;
        if ($e = Validator::validatePassword($pass))
            $errors[] = $e;
        $user = Validator::getUserByLogin($login);
        if($user !== null) {
            if ($e = Validator::validateLoginPassword($user, $pass))
                $errors[] = $e;
        } else {
            $errors[] = 'This Login does not exist';
        }
        if($errors) {
            include_once ROOT.'/view/user/login.php';
            return;
        }
        Auth::logIn($user->id);
        header('Location: /cabinet');
        return;
    }

    public function logout() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') Router::response_404();
        Auth::logOut();
        header('Location: /');
    }

    /* ---------- crud operations --------- */

    public function cabinet($id = null) {
        if(!$user = Auth::user()) header('Location: /login');
        if($id !== null) {
            if($user->role === 'admin')
                $user = User::find($id);
            else Router::response_404();
        }
        require_once ROOT . '/view/user/cabinet.php';
    }

    /**
     * List of all Users.
     */
    public function index() {
        $list = User::all();
        require_once ROOT . '/view/user/index.php';
    }

    /**
     * New User Registration form.
     */
    public function create() {
        require_once ROOT . '/view/user/create.php';
    }

    /**
     * Complete new User registration.
     */
    public function store() {

        if($_SERVER['REQUEST_METHOD'] !== 'POST') Router::response_404();

        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = false;
        if($e = Validator::validateLoginEntry($login))
            $errors[] = $e;
        if($e = Validator::validateEmail($email))
            $errors[] = $e;
        if($e = Validator::validateRegisterEmail($email))
            $errors[] = $e;
        if ($e = Validator::validatePassword($password))
            $errors[] = $e;

        if($errors) {
            include_once ROOT.'/view/user/create.php';
            return;
        } else {
            $user = new User([$login, $email, $password]);
            $user->save();
            Auth::logIn($user->id);
            header('Location: /cabinet');
            return;
        }
    }

    public function edit($id) {
        if(!$user = Auth::user()) header('Location: /login');
        if($user->id != $id)
            if ($user->role === 'admin')
                $user = ($user->id === $id) ? $user : User::find($id);
            else Router::response_404();
        $_SESSION['user_email_before_update'] = $user->email;
        require_once ROOT . '/view/user/edit.php';
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') Router::response_404();
        if(!is_token()) redirect('login');
        if(!$user = Auth::user()) redirect('login');
        $id = $_POST['id'];

        // Flag for redirect Admin to user profile after update
        $admin = false;
        // Is this not an account owner?
        if($user->id != $id) { // Is this Admin then?
            if ($user->role === 'admin') {
                $user = ($user->id == $id) ? $user : User::find($id);
                $admin = true;
            } else Router::response_404();
        }

        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = false;
        if($e = Validator::validateLoginEntry($login))
            $errors[] = $e;
        if($e = Validator::validateEmail($email))
            $errors[] = $e;
        if($e = Validator::validateUpdateEmail($email))
            $errors[] = $e;
        if ($e = Validator::validateUpdatePassword($password))
            $errors[] = $e;

        if($errors) {
            include_once ROOT.'/view/user/edit.php';
            return;
        } else {
            $user->login = $login;
            $user->email = $email;
            if($password)
                $user->password = $password;
            $user->save();
            unset_old_form_params();
            redirect('/cabinet' . ($admin ? ('/' . $id) : ''));
        }
    }

    public function destroy() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        $id = $_POST['id'];
        if(!$user = Auth::user()) redirect('login');

        // Check the owner
        if($user->id != $id) {
            if ($user->role === 'admin') { // Check the rights
                $user = ($user->id == $id) ? $user : User::find($id);
                $user->delete();
                redirect('/user/list');
            }
            else {
                redirect('/');
            }
        }
        $user->delete();
        Auth::logOut();
        redirect('/');
    }

}