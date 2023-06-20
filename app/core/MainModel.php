<?php

namespace Model;

class MainModel
{
  use Database;
  protected $table;
  protected $limit;
  protected $offset;
  protected $order_by;
  protected $order_by_type;
  protected $sql;
  protected $val = [];
  protected $allowedColumns = [];
  protected $errors = [];
  protected $primaryKey = 'id';
  protected $validationRules = [];
  protected $onUpdateValidationRules = [];
  protected $onInsertValidationRules = [];

  // check if column is allowed
  private function checkAllowedColumn(&$data)
  {

    foreach ($data as $key => $value) {
      if (!in_array($key, $this->allowedColumns)) {
        unset($data[$key]);
      }
    }
  }
  private function checkAllAllowedColumn(&$setData = [], &$whereEqual = [],  &$whereNotEqual = [], &$whereOr = [])
  {
    if (!empty($setData)) $this->checkAllowedColumn($setData);
    if (!empty($whereEqual)) $this->checkAllowedColumn($whereEqual);

    if (!empty($whereNotEqual)) $this->checkAllowedColumn($whereNotEqual);
    if (!empty($whereOr)) $this->checkAllowedColumn($whereOr);
  }


  // build where clause
  private function where($whereEqual = [], $whereNotEqual = [],  $whereOr = [])
  {
    if (!empty($whereEqual) || !empty($whereNotEqual) || !empty($whereOr)) {
      $this->sql .= ' WHERE ';
    }
    if (!empty($whereEqual)) {
      foreach ($whereEqual as $key => $value) {
        $this->sql .= $key . ' = ? AND ';
        array_push($this->val, $value);
      }
    }

    if (!empty($whereNotEqual)) {
      foreach ($whereNotEqual as $key => $value) {
        $this->sql .= $key . ' != ? AND ';
        array_push($this->val, $value);
      }
    }
    if (!empty($whereOr) && isset($whereOr)) {

      $this->sql .= '(';
      foreach ($whereOr as  $v) {
        foreach ($v as $key => $value) {
          $this->sql .= $key . ' = ? OR ';
          array_push($this->val, $value);
        }
      }
      $this->sql = rtrim($this->sql, ' OR ');
      $this->sql .= ')';
    }

    $this->sql = rtrim($this->sql, ' AND ');
  }

  // Select related methods
  public function orderBy($order_by = 'id', $order_by_type = 'DESC')
  {
    $this->order_by = $order_by;
    $this->order_by_type = $order_by_type;
    $this->sql .= ' ORDER BY ' . $this->order_by . ' ' . $this->order_by_type;
    return $this;
  }

  public function limit($limit)
  {
    $this->limit = $limit;
    $this->sql .= ' LIMIT ' . $this->limit;
    return $this;
  }
  public function offset($offset)
  {
    $this->offset = $offset;
    $this->sql .= ' OFFSET ' . $this->offset;
    return $this;
  }
  public function select($whereEqual = [], $whereNotEqual = [],  $whereOr = [])
  {

    $this->sql = 'SELECT * FROM ' . $this->table;
    $this->where($whereEqual, $whereNotEqual, $whereOr);

    return $this;
  }
  public function get()
  {
    echo $this->sql;
    return $this->query($this->sql, $this->val);
  }

  public function getFirst()
  {
    $this->limit(1);
    return $this->query($this->sql, $this->val)[0];
  }

  // Insert related methods
  public function insert($columnsAndValues)
  {
    if (!empty($this->allowedColumns) && isset($this->allowedColumns)) {
      $this->checkAllowedColumn($columnsAndValues);
    }

    $keys = array_keys($columnsAndValues);
    $this->sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', $keys) . ') VALUES (:' . implode(',:', $keys) . ')';
    $this->query($this->sql, $columnsAndValues);
  }

  public function update($setData, $whereEqual = [],  $whereNotEqual = [], $whereOr = [])
  {
    if (!empty($this->allowedColumns) && isset($this->allowedColumns)) {
      $this->checkAllAllowedColumn($setData, $whereEqual, $whereNotEqual, $whereOr);
    }

    $this->sql = 'UPDATE ' . $this->table . ' SET ';
    foreach ($setData as $key => $value) {
      $this->sql .= $key . ' = ?, ';
      $this->val[] = $value;
    }
    $this->sql = rtrim($this->sql, ', ');
    $this->where($whereEqual, $whereNotEqual, $whereOr);
    echo $this->sql;
    // $this->query($this->sql, $this->val);
    return false;
  }
  // Delete related methods
  public function delete($whereEqual,  $whereNotEqual = [], $whereOr = [])
  {
    $this->sql = 'DELETE FROM ' . $this->table;
    $this->where($whereEqual, $whereNotEqual, $whereOr);

    $this->query($this->sql, $this->val);
    return false;
  }

  // get individual error
  public function getError($key)
  {
    if (!empty($this->errors[$key]))
      return $this->errors[$key];

    return "";
  }

  // get primary key
  protected function getPrimaryKey()
  {
    return $this->primaryKey;
  }

  // validate data
  public function validate($data)
  {

    $this->errors = [];
    if (!empty($this->primaryKey) && !empty($data[$this->primaryKey])) {
      $validationRules = $this->onUpdateValidationRules;
    } else {

      $validationRules = $this->onInsertValidationRules;
    }


    if (!empty($this->validationRules)) {
      foreach ($this->validationRules as $column => $rules) {

        if (!isset($data[$column]))
          continue;

        foreach ($rules as $rule) {

          switch ($rule) {
            case 'required':

              if (empty($data[$column]))
                $this->errors[$column] = ucfirst($column) . " is required";
              break;
            case 'email':

              if (!filter_var(trim($data[$column]), FILTER_VALIDATE_EMAIL))
                $this->errors[$column] = "Invalid email address";
              break;
            case 'alpha':

              if (!preg_match("/^[a-zA-Z]+$/", trim($data[$column])))
                $this->errors[$column] = ucfirst($column) . " should only have alphabetical letters without spaces";
              break;
            case 'alpha_space':

              if (!preg_match("/^[a-zA-Z ]+$/", trim($data[$column])))
                $this->errors[$column] = ucfirst($column) . " should only have alphabetical letters & spaces";
              break;
            case 'alpha_numeric':

              if (!preg_match("/^[a-zA-Z0-9]+$/", trim($data[$column])))
                $this->errors[$column] = ucfirst($column) . " should only have alphabetical letters & spaces";
              break;
            case 'alpha_numeric_symbol':

              if (!preg_match("/^[a-zA-Z0-9\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
                $this->errors[$column] = ucfirst($column) . " should only have alphabetical letters, number & spaces";
              break;
            case 'alpha_symbol':

              if (!preg_match("/^[a-zA-Z\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
                $this->errors[$column] = ucfirst($column) . " should only have alphabetical letters & spaces";
              break;

            case 'not_less_than_8_chars':

              if (strlen(trim($data[$column])) < 8)
                $this->errors[$column] = ucfirst($column) . " should not be less than 8 characters";
              break;

            case 'unique':

              $key = $this->getPrimaryKey();
              if (!empty($data[$key])) {
                //edit mode
                if ($this->select([$column => $data[$column]], [$key => $data[$key]])->getFirst()) {
                  $this->errors[$column] = ucfirst($column) . " should be unique";
                }
              } else {
                //insert mode
                if ($this->select([$column => $data[$column]])->getFirst()) {
                  $this->errors[$column] = ucfirst($column) . " should be unique";
                }
              }
              break;

            default:
              $this->errors['rules'] = "The rule " . $rule . " was not found!";
              break;
          }
        }
      }
    }

    if (empty($this->errors)) {
      return true;
    }

    return false;
  }
}
