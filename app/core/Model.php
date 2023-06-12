<?php

namespace Model;

class Model
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
        return $this->query($this->sql, $this->val);
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
}
