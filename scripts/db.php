<?php

global $db;
$db = new PDO('mysql:host=' . conf('db_host') . ';dbname=' . conf('db_name'), conf('db_user'), conf('db_psw'),
  array(PDO::MYSQL_ATTR_FOUND_ROWS => true, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

function db_row($stmt) {
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function db_error() {
  global $db;
  return $db->errorInfo();
}

function db_query($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $q->execute($args);
  if ($res) {
    while ($row = db_row($q)) {
      if (isset($row['id']) && !isset($r[$row['id']])) {
        $r[$row['id']] = $row;
      }
      else {
        $r[] = $row;
      }
    }
  }
  return $r;
}

function db_result($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $q->execute($args);
  if ($res) {
    if ($row = db_row($q)) {
      return current($row);
    }
    else {
      return FALSE;
    }
  }
  else {
    return FALSE;
  }
}

function db_command($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  return $res = $q->execute($args);
}

function db_insert_id() {
  global $db;
  return $db->lastInsertId();
}

function db_get($table_name,$column,$where,$val, $default = FALSE) {
  if (strlen($val) == 0 || strlen($table_name) == 0 || strlen($column) == 0 || strlen($where) == 0) {
    return $default;
  }
  $command="SELECT ".$column." from ".$table_name." where ".$where."=?";
  $value = db_result($command, $val);
  if ($value === FALSE) {
    return $default;
  }
  else {
    return $value;
  }
}
function db_get_last($table_name,$column,$order_col, $default = FALSE) {
  if (strlen($table_name) == 0 || strlen($column) == 0) {
    return $default;
  }
  $command="SELECT ".$column." FROM ".$table_name." ORDER BY ".$order_col." DESC LIMIT 1";
  $value = db_result($command);
  if ($value === FALSE) {
    return $default;
  }
  else {
    return $value;
  }
}

function db_set($table_name,$column,$def,$name, $value) {
  if (strlen($name) == 0 || strlen($table_name) == 0 || strlen($column) == 0 || strlen($def) == 0) {
    return;
  }
  $v = db_get($table_name,$column,$def,$name);
  if ($v === FALSE) {
    $q = "INSERT INTO ".$table_name." (".$column.",".$def.") VALUES (?,?)";
    return db_command($q,$value,$name) > 0;
  }
  else {
    $q = "UPDATE ".$table_name." SET ".$column." = ? WHERE ".$def." = ?";
    return db_command($q, $value, $name) > 0;
  }
}
function db_insert($table_name,$column,$where,$where_val,$value){
  if (strlen($where_val) == 0 || strlen($table_name) == 0 || strlen($column) == 0 || strlen($where) == 0) {
    return;
  }
  $q = "INSERT INTO ".$table_name." (".$column.",".$where.") VALUES (?,?)";
  return db_command($q,$value,$where_val) > 0;
}
function db_rmAll($table_name,$column,$where,$val){
  if (strlen($val) == 0 || strlen($table_name) == 0 || strlen($column) == 0 || strlen($where) == 0) {
    return;
  }
  $v = db_get($table_name,$column,$where,$val);
  if ($v === FALSE) {
    return;
  }
  else {
    $q = "DELETE FROM ".$table_name." WHERE ".$where." = ?";
    return db_command($q,$val) > 0;
  }
}
function db_sort_sql() {
}

function db_pager_query() {
}

function db_array() {
  global $db;
  $args = func_get_args();
  $key = array_shift($args);
  $query = array_shift($args);
  $q = $db->prepare($query);
  $res = $q->execute($args);
  $r = array();
  if ($res) {
    while ($row = db_row($q)) {
      if (!empty($key) && isset($row[$key]) && !isset($r[$row[$key]])) {
        $r[$row[$key]] = $row;
      }
      else {
        $r[] = $row;
      }
    }
  }
  return $r;
}
