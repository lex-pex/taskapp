<?php
namespace Db\Models;
require_once ROOT . '/db/api/Model.php';
use Db\Api\Model;

class Task extends Model
{
    public $table = 'tasks';

    /**
     * @var array field names for the Model
     */
    public $fields = ['user_name', 'email', 'text', 'status', 'user_id', 'created_at', 'updated_at'];

}
