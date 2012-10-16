<?php

namespace zero\data;

use PDO;

class Table_Pdo {
  private $pdo;
  private $tableName;

  public function __construct(PDO $pdo, $tableName) {
    $this->pdo = $pdo;
    $this->tableName = $tableName;
  }

  public function create(array $data) {
    $sql = $this->insertSql($data);
    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }

  public function read($query = null) {
    $sql = $this->selectSql($query);
    $statement = $this->pdo->prepare($sql);
    $statement->execute($query);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }

  public function update($query, array $update) {

  }

  public function delete($query) {

  }

  private function insertSql(array $data) {
    $keys = array_keys($data);
    $fields = implode(', ', $keys);
    $values = ':'.implode(', :', $keys);
    $sql = "INSERT INTO $this->tableName ($fields) VALUES ($values)";
    return $sql;
  }

  private function selectSql($query) {
    $sql = "SELECT * FROM $this->tableName";
    if (!empty($query)) {
      $keys = array_keys($query);
      $equals = array_map(function($key) { return "$key = :$key"; }, $keys);
      $where = implode(' AND ', $equals);
      $sql = "$sql WHERE $where";
    }
    return $sql;
  }
}
