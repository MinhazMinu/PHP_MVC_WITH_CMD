<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');
class User extends Model
{
    protected $table = "Users";

    protected $allowedColumns = ['name', 'age', 'date'];
}
