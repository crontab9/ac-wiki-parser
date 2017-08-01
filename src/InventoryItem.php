<?php

namespace AcWikiParser;

class InventoryItem implements \JsonSerializable {

    protected $name;
    protected $cost;

    public function __construct($name, $cost)
    {
        $this->name=$name;
        $this->cost=$cost;
    }

    public function jsonSerialize()
    {
        return [
            'Name'=>$this->name,
            'Cost'=>$this->cost
        ];
    }
}