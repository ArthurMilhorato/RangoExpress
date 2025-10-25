<?php
class Order {
    public $id;
    public $user_id;
    public $total;
    public $status;
    public $created_at;
    public $user_name;
    public $items = [];

    public function __construct($user_id = 0, $total = 0) {
        $this->user_id = $user_id;
        $this->total = $total;
        $this->status = 'pendente';
    }
}
?>