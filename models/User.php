<?php
class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $is_admin;
    public $created_at;

    public function __construct($name = '', $email = '', $password = '', $is_admin = false) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->is_admin = $is_admin;
    }
}
?>