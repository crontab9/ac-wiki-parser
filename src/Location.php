<?php

namespace AcWikiParser;

class Location implements \JsonSerializable {

    protected $nsCoord;
    protected $ewCoord;

    public function parseFromWikiString($string)
    {
        preg_match('/[0-9]{1,2}\.\d[NS]\,\s[0-9]{1,2}\.\d[EW]/', $string, $coords);


        $coords = explode(',', $coords[0]);

        $this->nsCoord=$coords[0];
        $this->ewCoord=$coords[1];
    }


    public function __toString()
    {
        if(!empty($this->nsCoord) && !empty($this->ewCoord))
        {
            return $this->nsCoord . ', ' . $this->ewCoord;
        }

        return null;
    }

    public function jsonSerialize()
    {
        return [$this->__toString()];
    }
}