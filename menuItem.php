<?php


class Menuitem
{
    private $itemName;
    private $description;
    private $price;

    // CONSTRUCTOR
    public function __construct($itemName, $description, $price)
    {
        $this->itemName = $itemName;
        $this->description = $description;
        $this->price = $price;
    }

    // Returns the item's name
    public function get_itemName() {
        return $this->itemName;
    }

    // Returns the item's description
    public function get_description() {
        return $this->description;
    }

    // Returns the item's price
    public function get_price() {
        return $this->price;
    }


}

?>