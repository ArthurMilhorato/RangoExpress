<?php
class Item {
    public $id;
    public $name;
    public $description;
    public $price;
    public $image;
    public $active;
    public $created_at;

    public function __construct($name = '', $description = '', $price = 0, $image = '') {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->active = true;
    }
}
?>