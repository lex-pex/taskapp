<?php

namespace Controllers;

require_once ROOT . '/db/util/Connection.php';

use Db\Util\Connection;

/**
 * Create and populate given database schema 
 */
class MigrationsSeedsController {

    /**
     * @var \PDO - connection object
     */
    private static $db;

    /**
     * Admin Function
     * MigrationsSeedsController constructor.
     * Initiate the db-connection
     */
    public function __construct() {
        self::$db = Connection::getConnection();
    }

    /**
     * Run migrations and seeds
     */
    public function start() {
        // create needed tables
        $this->create_all_tables();
        // populate with initial data
        $this->seed_all_tables();
        return redirect('/');
    }

    /**
     * Run migrations
     */
    private function create_all_tables() {
        $this->create_users_table();
        $this->create_tasks_table();
    }

    /**
     * Run seeds
     */
    private function seed_all_tables() {
        $this->seed_users_table();
        $this->seed_tasks_table();
    }

    // __________________ S E E D E R S _________________
    // __________________________________________________

    /**
     * Seed the 'tasks' table for current application
     * id, user_name, email, text, status, user_id (timestamps)
     */
    private function seed_tasks_table() {

        $table = 'tasks';
        $fields = ['user_name', 'email', 'text', 'status', 'created_at'];

        for($i = 1; $i <= 25; $i ++) {
            $user_name = 'User_' . $i;
            $user_email = 'user' . $i . '@mail.com';
            $text = 'Absolutely brand new solution, as a task management application';
            $status = $i % 2;
            $text = $status ? 'Absolutely brand new solution, as a task management application' :
                'Despite our dissatisfaction when being bombarded by all the advertisers\' information we must admit that they do perform a useful service to society';
            $created_at = date('Y-m-d H:i:s', time());
            $data = [$user_name, $user_email, $text, $status, $created_at];

            $this->insert($table, $fields, $data);
        }
    }

    /**
     * Seed the 'users' table for current application
     * Fields: id, login, email, password, role, (timestamps)
     */
    private function seed_users_table() {
        $table = 'users';
        $fields = ['login', 'email', 'password', 'role', 'created_at'];
        $ids = 'Table "' . $table . '" Id\'s inserted: ';

        $timestamp = date('Y-m-d H:i:s', time());

        // create init Admin, return string list inserted id
        $this->insert($table, $fields, ['admin', 'admin@mail.com', '123', 'admin', $timestamp]);

        // create 10 init users
        for($i = 1; $i <= 10; $i ++) {
            $data = ['User_' . $i, 'user' . $i . '@mail.com', '321', 'user', $timestamp];
            $this->insert($table, $fields, $data);
        }
    }

    /**
     * Insert one record into specified table
     * @param string $table
     * @param array $fields - list of fields
     * @param array $a - list of corresponding data
     * @return int - last insert 'id' or '0' on fail
     */
    private function insert(string $table, array $fields, array $a = []) : int {
        $names = '';
        $wildcards = '';
        foreach ($fields as $n => $f)
            if(is_numeric($n)) {
                $names .= "$f, ";
                $wildcards .= ":$f, ";
            }
        $names = trim($names, ', ');               // field, field ...
        $wildcards = trim($wildcards, ', ');       // :field, :field ...
        $query = "INSERT INTO $table ($names) VALUES ($wildcards)";
        $res = self::$db->prepare($query);
        foreach ($fields as $n => $f)
            if(is_numeric($n))
                $res->bindParam(":$f", $a[$n]);
        if ($res->execute()) {
            return self::$db->lastInsertId();
        }
        return 0;
    }

    // _______________ M I G R A T I O N S ______________
    // __________________________________________________

    /**
     * Create the 'users' table for current application
     * Fields: id, login, email, password, role, (timestamps)
     */
    private function create_users_table() {
        $sql =  "CREATE TABLE IF NOT EXISTS users (
                 id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                 login VARCHAR( 128 ) NOT NULL UNIQUE,
                 email VARCHAR( 128 ) NOT NULL UNIQUE,
                 password VARCHAR( 128 ) NOT NULL,
                 role VARCHAR( 32 ) NOT NULL DEFAULT 'user',
                 created_at TIMESTAMP NULL,
                 updated_at TIMESTAMP NULL);";
        self::$db->exec($sql);
    }

    /**
     * Create the 'tasks' table for current application
     * Fields: id, user_name, email, text, status, user_id (timestamps)
     */
    private function create_tasks_table() {
        $sql = "CREATE TABLE IF NOT EXISTS tasks (
                id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                user_name VARCHAR( 128 ) NOT NULL DEFAULT '',
                email VARCHAR( 128 ) NOT NULL DEFAULT '',
                text VARCHAR( 512 ) NOT NULL DEFAULT '',
                status INT( 1 ) NOT NULL DEFAULT 0,
                user_id INT( 11 ) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL);";
        self::$db->exec($sql);
    }

}








