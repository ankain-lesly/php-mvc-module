<?php

/**
 * User: Dev_Lee
 * Date: 6/29/2023
 * Time: 6:00 AM
 */

namespace app\database;

/**
 * Class Database
 *
 * @author  Ankain Lesly <leeleslyank@gmail.com>
 * @package app\-> Models
 */

class Database
{
  public static string $DB_HOST;
  public static string $DB_USER;
  public static string $DB_PASSWORD;
  public static string $DB_NAME;


  public static \PDO $pdo;

  public function __construct($dbConfig = [])
  {
    $host = $dbConfig['host'] ?? self::$DB_HOST;
    $name = $dbConfig['name'] ?? self::$DB_NAME;
    $username = $dbConfig['user'] ?? self::$DB_USER;
    $password = $dbConfig['password'] ?? self::$DB_PASSWORD;;

    $dns = 'mysql:host=' . self::$DB_HOST . ';dbname=' . self::$DB_NAME;

    self::$pdo = new \PDO($dns, $username, $password);
    self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  public static function prepare($sql): \PDOStatement
  {
    return self::$pdo->prepare($sql);
  }
}
