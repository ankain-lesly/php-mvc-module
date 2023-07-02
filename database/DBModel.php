<?php

/**
 * User: Dev_Lee
 * Date: 6/29/2023
 * Time: 6:00 AM
 */

namespace app\database;

use app\database\Database;
use app\models\BaseModel;
use PDO;

/**
 * Class DBModel
 *
 * @author  Ankain Lesly <leeleslyank@gmail.com>
 * @package app\-> Models
 */
abstract class DBModel extends BaseModel
{
  abstract public static function tableName(): string;
  // abstract public function getDisplayName(): string;

  public static function SetDatabaseDetails(array $DB_CONFIG)
  {
    Database::$DB_HOST = $DB_CONFIG['host'] ?? die('Database configurations "host" is required');
    Database::$DB_USER = $DB_CONFIG['user'] ?? die('Database configurations "user" is required');
    Database::$DB_PASSWORD = $DB_CONFIG['password'] ?? die('Database configurations "password" is required');
    Database::$DB_NAME = $DB_CONFIG['name'] ?? die('Database configurations "name" is required');
  }
  public static function primaryKey(): string
  {
    return 'id';
  }

  public function save(array $data)
  {
    $this->loadData($data);

    if (!$this->validate()) {
      return ["errors" => $this->getErrors()];
    }

    $tableName = $this->tableName();
    $attributes = $this->attributes();

    $params = array_map(fn ($attr) => ":$attr", $attributes);

    $sql = "INSERT INTO $tableName (" . implode(",", $attributes) . ") 
                VALUES (" . implode(",", $params) . ")";

    // $statement = $this->PDO->prepare($sql);
    $statement = $this->PDO->prepare($sql);

    foreach ($attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute});
    }

    return $statement->execute();
  }

  // public static function prepare($sql): PDOStatement
  // {
  //   return $this->PDO->prepare($sql);
  // }

  // Query the Database
  public function insert(string $query, array $params)
  {
    $stmt = $this->PDO->prepare($query);
    $stmt->execute($params);

    $id = $this->PDO->lastInsertId();
    // $this->conn = null;
    return $id;
  }

  // Query Data
  public function query(string $query, array $params)
  {
    $stmt = $this->PDO->prepare($query);
    $stmt->execute($params);

    return $stmt->rowCount();
  }


  public function findOne($where, $select_array = null)
  {
    /**
     * $select_array
     * ['id', 'title', 'post_boby', 'created_at']
     */

    $select_list = " * ";
    if ($select_array && is_array($select_array)) {
      $select_list = implode(", ", $select_array);
    }

    $tableName = static::tableName();
    $attributes = array_keys($where);

    $sql_where = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
    $sql = "SELECT $select_list FROM $tableName WHERE $sql_where";

    $statement = $this->PDO->prepare($sql);
    foreach ($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }

    $statement->execute();
    // $data = $statement->fetchObject(static::class);
    return $statement->fetchObject(static::class);
  }

  // Fetch Custom Data Array
  public function
  findAll($where, $select_array = null)
  {
    /**
     * $select_array
     * ['id', 'title', 'post_boby', 'created_at']
     */

    $select_list = " * ";
    if ($select_array && is_array($select_array)) {
      $select_list = implode(
        ", ",
        $select_array
      );
    }

    $tableName = static::tableName();
    $attributes = array_keys($where);

    $sql_where = implode(
      " AND ",
      array_map(fn ($attr) => "$attr = :$attr", $attributes)
    );
    $sql = "SELECT $select_list FROM $tableName WHERE $sql_where";

    $statement = $this->PDO->prepare($sql);
    foreach ($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }

    $statement->execute();
    $data = array();

    if ($statement->rowCount() > 0) {
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
      }
      return $data;
    }

    return false;
  }
  // Fetch All Custom Data Array
  public function fetch(string $query)
  {
    $stmt = $this->PDO->query($query);

    // $this->conn = null;
    $data = array();

    if ($stmt->rowCount() > 0) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
      }
      return $data;
    }

    return false;
  }

  // Fetch All Custom Data Array
  public function fetchCount(string $table = null, string $query = '')
  {
    if ($query)
      $stmt = $this->PDO->query($query);
    else {
      $sql = "SELECT COUNT(*) AS count FROM $table";
      $stmt = $this->PDO->query($sql);
    }

    return $stmt->fetch();
  }
}
