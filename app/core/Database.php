<?php

trait Database
{
    private function connect()
    {
        $string = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;

        return  new PDO($string, DB_USER, DB_PASS);
    }

    public function query($query, $data = [])
    {
        $statement = $this->connect()->prepare($query);
        $check =  $statement->execute($data);
        if ($check) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
        }
        return false;
    }
}
