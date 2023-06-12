<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');
class User extends MainModel
{
    protected $table = "Users";
    protected $loginUniqueColumn = 'userid';

    protected $allowedColumns = ['username', 'email', 'userid', 'password', 'date'];

    /*****************************
     * 	rules include:
		required
		alpha
		email
		numeric
		unique
		symbol
		longer_than_8_chars
		alpha_numeric_symbol
		alpha_numeric
		alpha_symbol
     *
     ****************************/
    protected $validationRules = [

        'email' => [
            'email',
            'unique',
            'required',
        ],
        'username' => [
            'alpha',
            'required',
        ],
        'password' => [
            'not_less_than_8_chars',
            'required',
        ],
    ];



    public function signup($data)
    {
        if ($this->validate($data)) {
            //add extra user columns here
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['date'] = date("Y-m-d H:i:s");
            // $data['date_created'] = date("Y-m-d H:i:s");

            $this->insert($data);
            redirect('login');
        }
    }

    public function login($data)
    {
        $row = $this->select([$this->loginUniqueColumn => $data[$this->loginUniqueColumn]])->getFirst();

        if ($row) {

            //confirm password
            if (password_verify($data['password'], $row->password)) {
                $ses = new \Core\Session;
                $ses->auth($row);
                redirect('home');
            } else {
                $this->errors[$this->loginUniqueColumn] = "Wrong $this->loginUniqueColumn or password";
            }
        } else {
            $this->errors[$this->loginUniqueColumn] = "Wrong $this->loginUniqueColumn or password";
        }
    }
}
