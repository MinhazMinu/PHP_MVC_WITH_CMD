<?php

class Model
{
    use Database;
    protected $table = 'users';
    protected $limit;
    protected $offset;
    protected $order_by;
    protected $order_by_type;
    protected $sql;
    protected $val = [];

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
    public function select($data = [], $data_not = [])
    {

        $this->sql = 'SELECT * FROM ' . $this->table;

        if (!empty($data) || !empty($data_not)) {
            $this->sql .= ' WHERE ';
        }
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->sql .= $key . ' = ? AND ';
                array_push($this->val, $value);
            }
        }
        if (!empty($data_not)) {
            foreach ($data_not as $key => $value) {
                $this->sql .= $key . ' != ? AND ';
                array_push($this->val, $value);
            }
        }
        $this->sql = rtrim($this->sql, ' AND ');
        return $this;
    }
    public function get()
    {
        return $this->query($this->sql, $this->val);
    }

    public function getFirst()
    {
        $this->limit(1);
        return $this->query($this->sql, $this->val);
    }

    // Insert related methods
    public function insert($data)
    {
        $keys = array_keys($data);
        $this->sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', $keys) . ') VALUES (:' . implode(',:', $keys) . ')';
        $this->query($this->sql, $data);
    }
    public function update($id, $data, $id_column = 'id')
    {
    }
    public function delete($id, $id_column = 'id')
    {
    }
}
