<?php

/**
 * User: Dev_Lee
 * Date: 6/29/2023
 * Time: 6:00 AM
 */

namespace app\database;

use app\database\Database;
use app\models\BaseModel;

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

    $statement = Database::prepare($sql);

    foreach ($attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute});
    }

    return $statement->execute();
  }

  // public static function prepare($sql): \PDOStatement
  // {
  //   return Database::prepare($sql);
  // }

  public static function findOne($where)
  {
    $tableName = static::tableName();
    $attributes = array_keys($where);
    $sql = implode("AND", array_map(fn ($attr) => "$attr = :$attr", $attributes));

    // $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

    $statement = Database::prepare("SELECT * FROM $tableName WHERE $sql");
    foreach ($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }
    $statement->execute();
    return $statement->fetchObject(static::class);
  }
}
