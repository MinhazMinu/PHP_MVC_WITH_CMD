<?php

class User extends Model
{
    protected $table = "Users";

    protected $allowedColumns = ['name', 'age', 'date'];
}
