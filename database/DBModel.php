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

  // public static function primaryKey(): string
  // {
  //   return 'id';
  // }

  // Insert Data
  public function insert(array $data)
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

    $statement = $this->PDO->prepare($sql);

    foreach ($attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute});
    }

    return $statement->execute() ? $data : false;
  }

  // Update Data
  public function update(array $data, array $where)
  {
    $this->loadData($data);

    if (!$this->validate($data)) {
      return ["errors" => $this->getErrors()];
    }

    $tableName = $this->tableName();

    $where_params = array();

    foreach ($where as $key) {
      if (array_key_exists($key, $data)) {
        $where_params[$key] = $data[$key];
        unset($data[$key]);
      }
    }

    $attributes = array_keys($data);

    $where_sql = array_map(fn ($attr) => "$attr = :$attr", $where);

    $params = array_map(fn ($attr) => "$attr = :$attr", $attributes);

    $sql = "UPDATE $tableName SET " . implode(",", $params) . "
            WHERE " . implode(" AND ", $where_sql);

    $statement = $this->PDO->prepare($sql);
    $final_attributes = array_merge($attributes, $where);

    foreach ($final_attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute});
    }
    return $statement->execute() ? $data : false;
  }
  // Delete Data
  public function delete($where)
  {

    $tableName = static::tableName();
    $attributes = array_keys($where);

    $sql_where = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
    $sql = "DELETE FROM $tableName WHERE $sql_where";

    $statement = $this->PDO->prepare($sql);
    foreach ($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }

    $statement->execute();
    return $statement->rowCount();
  }

  // Find Single Object 
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
    // return $statement->fetchObject(static::class);
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  // Find a Collection of objects
  public function findAll(
    $where = [],
    $select_array = null,
    array $pagination = [
      "current_page" => 1,
      "page_limit" => 10,
      "order_by" => '',
    ]
  ) {
    // $pagination = [
    //   "current_page" => 2,
    //   "page_limit" => 3,
    //   "order_by" => "id",
    // ];

    $pagination_sql = '';
    $order_sql = '';

    if (!empty($pagination)) {
      $start_at = ($pagination['current_page'] - 1) * $pagination['page_limit'];
      $pagination_sql = "LIMIT " . ($start_at) . ", " . $pagination['page_limit'];
      $order_sql = $pagination['order_by'] ? "ORDER BY " . $pagination['order_by'] . " DESC" : '';
    }

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

    $sql_where = $sql_where ? "WHERE $sql_where" : '';

    $sql = "SELECT $select_list FROM $tableName $sql_where $order_sql $pagination_sql";

    $statement = $this->PDO->prepare($sql);
    foreach ($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }

    $statement->execute();

    // if ($statement->rowCount() <= 0) return [];

    $data = array();

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $data['data'][] = $row;
    }

    $total_rows = $this->findCount()['count'];
    $data['paginate_info'] = $pagination = [
      "current_page" => $pagination['current_page'] ?? 1,
      "total_pages" => round($total_rows / ($pagination['page_limit'] ?? 1)),
      "page_limit" => $pagination['page_limit'] ?? 0,
      "order_by" => $pagination['order_by'] ?? '',
      "total_rows" => $total_rows,
    ];

    return $data;
  }
  // Fetch Custom Query
  # ---
  // Fetch Data count
  public function findCount(string $table = null, array $where = [])
  {
    $tableName = $tableName ?? static::tableName();

    $attributes = array_keys($where);

    $sql_where = implode(
      " AND ",
      array_map(fn ($attr) => "$attr = :$attr", $attributes)
    );

    $sql_where = $sql_where ? "WHERE $sql_where" : '';

    $sql = "SELECT COUNT(*) AS count FROM $tableName $sql_where";

    $statement = $this->PDO->prepare($sql);
    foreach ($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }

    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
  }
}
