<?php

namespace app\database;

use app\models\BaseModel;
use PDO;

class DataAccess
{

  private PDO $PDO;

  public function __construct(PDO $pdo)
  {
    $this->PDO = $pdo;
  }
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

  // Fetch Custom Data
  public function findOne(string $query, array $params)
  {
    $stmt = $this->PDO->prepare($query);
    $stmt->execute($params);

    // $this->conn = null;
    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
  }

  // Fetch Custom Data Array
  public function findAll(string $query, array $params)
  {
    $stmt = $this->PDO->prepare($query);
    $stmt->execute($params);

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
