<?php

namespace Helpers;

require_once ROOT . '/db/models/User.php';

use Db\Models\User;

class Validator
{

    // Name Validation
    public static function validateName(string $name) : string {
        if(mb_strlen($name, 'utf-8') < 3 || mb_strlen($name, 'utf-8') > 64)
            return 'Name has to be more than 3 character and less 50';
        return '';
    }

    // Task Text Validation
    public static function validateText(string $text) : string {
        if(mb_strlen($text, 'utf-8') < 6 || mb_strlen($text, 'utf-8') > 512)
            return 'Text has to be more than 6 character and less 512';
        return '';
    }

    // Email Validation
    public static function validateEmail(string $email) : string {
        $pattern = '~^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$~';
        if(!preg_match($pattern, $email))
            return 'Email has to be real';
        return '';
    }

    // Email in DB validation on register
    public static function validateRegisterEmail(string $email) {
        if(User::exists('email', $email))
            return 'This email already exists';
        return '';
    }

    // Login Validation as a word
    public static function validateLoginEntry(string $login) {
        if(mb_strlen($login, 'utf-8') < 3 || mb_strlen($login, 'utf-8') > 30)
            return 'Login has to be from 5 to 30 characters';
        return '';
    }

    // Find of fail User by login. Return User or NULL
    public static function getUserByLogin(string $login) {
        $result = User::where('login', $login);
        if (count($result))
            return $result[0];
        return null;
    }

    // Email in DB validation on register
    public static function validateUpdateEmail(string $email) {
        if($_SESSION['user_email_before_update'] === $email)
            return '';
        if(User::exists('email', $email))
            return 'This email already exists';
        return '';
    }

    // Password Validation as a word
    public static function validatePassword(string $pass) {
        if(mb_strlen($pass, 'utf-8') < 2 || mb_strlen($pass, 'utf-8') > 30)
            return 'Password has to be from 5 to 30 characters';
        return '';
    }

    // Password Validation on Update as word
    public static function validateUpdatePassword(string $pass) {
        if($pass === '') return '';
        if(mb_strlen($pass, 'utf-8') < 2 || mb_strlen($pass, 'utf-8') > 30)
            return 'Password has to be from 5 to 30 characters';
        return '';
    }

    // Password Validation on Login
    public static function validateLoginPassword(User $user, string $pass) {
        if($user->password !== $pass)
            return 'Password not correct';
        return '';
    }
}


