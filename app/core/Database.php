<?php

namespace Model;

trait Database
{
    private function connect()
    {
        $string = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;

        return  new \PDO($string, DB_USER, DB_PASS);
    }

    public function query($query, $data = [])
    {
        try {
            $statement = $this->connect()->prepare($query);

            $check =  $statement->execute($data);

            if ($check) {
                $result = $statement->fetchAll(\PDO::FETCH_OBJ);

                if (is_array($result) && count($result) > 0) {
                    return $result;
                }
            }
        } catch (\PDOException $e) {
            throw new \Exception("Error : Mgs" . $e->getMessage() . "<br>File : " . $e->getLine(), 1);
        }

        return false;
    }

    public function get_row($query, $data = [])
    {
        $statement = $this->connect()->prepare($query);
        $check =  $statement->execute($data);
        if ($check) {
            $result = $statement->fetchAll(\PDO::FETCH_OBJ);
            if (is_array($result) && count($result) > 0) {
                return $result[0];
            }
        }
        return false;
    }
}
