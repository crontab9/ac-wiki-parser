<?php

namespace AcWikiParser;

class Inventory implements \JsonSerializable {


    protected $items;

    public function __construct()
    {
        $this->items=[];
    }

    public function addItem($name, $cost)
    {
        if(!empty($name))
        {
            $this->items[] = new InventoryItem($name, $cost);
        }
    }

    public function jsonSerialize()
    {
        return $this->items;
    }
}