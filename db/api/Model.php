<?php

namespace Db\Api;

require_once(ROOT . '/db/util/Connection.php');

use Db\Util\Connection;
use PDO;

class Model {

    /**
     * Fields in Table to interact at CRUD 
     */
    public $fields = [];

    /**
     * Table in DB representing the model 
     */
    public $table = '';

    /**
     * 2X2 level Array with Many-to-Many Relations Refers 
     * [
     *     ['has_many_table', 'linked_table'],
     *     ['wich_has_field', 'owner_field']
     * ]
     */
    public $related = [];

    public $id = 0;
    private static $db;

    public function __construct(array $fields = null) {
        $idx = 0;
        foreach ($this->fields as $key) {
            $this->fields[$key] = isset($fields[$idx]) ? $fields[$idx] : null;
            $idx ++;
        }
        self::$db = Connection::getConnection();
    }

    public function __set($name, $value) {
        if(array_key_exists($name, $this->fields)) {
            $this->fields[$name] = $value;
        }
    }

    public function __get($name) {
        return isset($this->fields[$name]) ? $this->fields[$name] : null;
    }

    /**
     * Determine whether current object is new
     * Call accordingly either "insert" or "update"
     */
    public function save() {
        if($this->id) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * Insert the mapping of current object into db table
     * @return object $this on success, or null
     */
    private function insert() {
        if(array_key_exists('created_at', $this->fields))
            $this->fields['created_at'] = date('Y-m-d H:i:s');
        $names = '';
        $wildcards = '';
        foreach ($this->fields as $n => $f)
            if(is_numeric($n) && $this->fields[$f] !== null) {
                $names .= "$f, ";
                $wildcards .= ":$f, ";
            }
        $names = trim($names, ', ');              // field, field ...
        $wildcards = trim($wildcards, ', ');      // :field, :field ...
        $query = "INSERT INTO $this->table ($names) VALUES ($wildcards)";
        $res = self::$db->prepare($query);
        foreach ($this->fields as $n => $f)
            if(is_numeric($n) && $this->fields[$f] !== null)
                $res->bindParam(":$f", $this->fields[$f]);
        if ($res->execute()) {
            $this->id = self::$db->lastInsertId();
            return $this;
        }
        return null;
    }

    /**
     * Update the mapping of current object into db table
     * @return object $this on success, or null
     */
    private function update() {
        if(array_key_exists('updated_at', $this->fields))
            $this->fields['updated_at'] = date('Y-m-d H:i:s');
        $q = '';
        foreach ($this->fields as $n => $f)
            if(is_numeric($n))
                $q .= "$f = :$f, ";
        $q = trim($q, ', '); // field = :field, field = :field
        $query = "UPDATE $this->table SET $q WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $this->id);
        foreach ($this->fields as $n => $f)
            if(is_numeric($n))
                $res->bindParam(":$f", $this->fields[$f]);
        if ($res->execute()) return $this;
        return null;
    }

    /**
     * Delete the record of current object from db table
     * @return int as amount of deleted table rows
     */
    public function delete() {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $this->id, PDO::PARAM_INT);
        $res->execute();
        return $res->execute();
    }

    /**
     *  Below are Static Functions' Interfaces and General Methods
     */

    /**
     * Static Method for check whether the record exists
     * @param string $key name of record
     * @param string $value is needed value of record
     * @return boolean is such record exists
     */
    public static function exists(string $key, string $value) {
        $className = get_called_class();
        $m = new $className();
        if(count($m->condition($key, $value, '=')))
            return true;
        return false;
    }

    /**
     * Static Method for delete a record in one touch
     * Delete the record of current object from db table
     * @return int as amount of deleted table rows
     */
    public static function destroy(int $id) {
        $className = get_called_class();
        $m = new $className();
        $table = $m->table;
        $query = "DELETE FROM $table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $res->execute();
        return $res->execute();
    }

    /**
     * Static Interface for object fucntion select()
     * Retrieve an instance from Db table and wrap it into this object
     * @param int $id exact position in the database
     * @return object $this model on success filled with db row, or null
     */
    public static function find($id) {
        $className = get_called_class();
        $m = new $className();
        return $m->select($id);
    }

    /**
     * Static Interface for object function selectAll()
     * Retrieve all rows from Db table and wrap its into $this object
     * @return array objects $this model on success filled with db row, or null
     */
    public static function all(bool $order = true) {
        $className = get_called_class();
        $m = new $className();
        return $m->selectAll($order);
    }

    /**
     * Static Interface for object fucntion slice()
     * Retrieve subset from table in Descending order
     * @param  int $offset start position from zero (excluding)
     * @param  int $limit end position (including) of subset from offset
     * @param  bool $lifo - descending / ascending order
     * @param  string $sort_by - sorting by ...
     * @return int as amount of returned table rows
     */
    public static function chunk(int $offset, int $limit, bool $lifo = true, string $sort_by = '') {
        $className = get_called_class();
        $m = new $className();
        return $m->slice($offset, $limit, $lifo, $sort_by);
    }

    /**
     * Static Interface for object function condition()
     * Retrieve subset from table according "where" clause
     * @param string $key, $value clause as key and needed value
     * @return array of model objects, or null
     */
    public static function where(string $key, string $value, string $symbol = '=') {
        $className = get_called_class();
        $m = new $className();
        return $m->condition($key, $value, $symbol);
    }

    /**
     * Retrieve an instance from Db table and wrap it into this object
     * @param int $id according id field in database
     * @return object $this model on success filled with db row, or null
     */
    public function select($id) {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $row = $res->fetch();
        $className = get_class($this);
        if($row) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            return $m;
        }
        return null;
    }

    /**
     * Retrieve all table records wrapping them into this entities
     * @param bool true adds the ascending order into query
     * @return array objects $this model on success filled with db row, or null
     */
    public function selectAll(bool $order = true) {
        $order = $order ? 'asc' : 'desc';
        $query = "SELECT * FROM $this->table ORDER BY id $order";
        $res = self::$db->prepare($query);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $className = get_class($this);
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $i++;
        }
        return $resultSet;
    }

    /**
     * Retrieve subset from table in Descending order
     * @param  int $offset start position from zero (excluding)
     * @param  int $limit end position (including) of subset from offset
     * @param  bool $lifo - descending / ascending order
     * @param  string $sort_by - sorting by ...
     * @return array as result set of table rows
     */
    public function slice(int $offset, int $limit, bool $lifo = true, string $sort_by = '') {
        $sort = $sort_by ? $sort_by : 'id';
        $order = $lifo ? 'DESC' : 'ASC';
        $query = "SELECT * FROM $this->table ORDER BY $sort $order LIMIT :limit OFFSET :offset";
        $res = self::$db->prepare($query);
        $res->bindParam(':offset', $offset, PDO::PARAM_INT);
        $res->bindParam(':limit', $limit, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $className = get_class($this);
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $i++;
        }
        return $resultSet;
    }

    /**
     * Static Interface for object function condition()
     * Retrieve subset from table according "where" clause
     * @param string $key = $valie clause as key and needed value
     * @return array of model objects, empty on bad condition
     */
    public function condition(string $key, string $value, string $symbol = '=') {
        $query = "SELECT * FROM $this->table WHERE $key $symbol :value";
        $res = self::$db->prepare($query);
        $res->bindParam(':value', $value);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $className = get_called_class();
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $i++;
        }
        return $resultSet;
    }

    /**
     * Retrieve amount of all records for current instance
     * @return int as amount of records of this model
     */
    public static function amount() {
        $className = get_called_class();
        $m = new $className();
        $query = "SELECT COUNT(*) FROM $m->table";
        $res = self::$db->prepare($query);
        $res->execute();
        $resultSet = $res->fetch();
        return +$resultSet[0];
    }
}






