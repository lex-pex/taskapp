<?php
namespace Db\Models;
require_once ROOT . '/db/api/Model.php';
use Db\Api\Model;

class User extends Model {
    public $table = 'users';

    // id, login, email, password, role, (timestamps)
    public $fields = ['login', 'email', 'password', 'role', 'created_at', 'updated_at'];
}
