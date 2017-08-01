<?php
namespace AcWikiParser;

use AcWikiParser\Exceptions\InvalidNpcException;

class Npc implements \JsonSerializable {

    protected $name;
    protected $location;
    protected $race;
    protected $title;
    protected $inventory;

    protected $node;

    const RACE = 'Race';
    const NAME = 'Name';
    const TITLE = 'Title';
    const LOCATION = 'Location';

    public function __construct()
    {
        $this->inventory=new Inventory();
        $this->location=new Location();
    }

    public function parseFromWikiNode(\DOMNode $node)
    {
        $this->node=$node;

        $this->parseStandardData();
        $this->parseInventoryData();

        $this->node=null;

        if(empty($this->name)){
            throw new InvalidNpcException('No name, invalid.');
        }
    }

    protected function parseStandardData()
    {

        preg_match('/\{\{NPC([\s\S]*)\}\}/msU', $this->node->nodeValue, $npcData);

        if(count($npcData) != 2 || strpos($npcData[1], "NPC Row") !== false)
        {
            throw new InvalidNpcException('Invalid NPC');
        }

        $npcData=$npcData[1];

        foreach(explode(PHP_EOL, $npcData) as $line)
        {
            $this->parseStandardLine($line);
        }

    }

    protected function parseInventoryData()
    {
        preg_match('/== Inventory ==([\S\s]*)\s== /msU', $this->node->nodeValue, $npcData);


        //no inventory
        if(count($npcData) != 2) return;

        $npcData=$npcData[1];

        preg_match_all('/{{Inventory Item .*/', $npcData, $inventory);

        $inventory=$inventory[0];
        if(count($inventory))
        {
            foreach ($inventory as $item)
            {
                $this->parseInventoryLine($item);
            }
        }


    }

    protected function parseInventoryLine($item)
    {
        $item=str_replace('}}', '', $item);
        $items=explode('|', $item);

        if(count($items) == 3)
        {
            $this->inventory->addItem(trim($items[1]), trim($items[2]));
        }
    }

    protected function parseStandardLine($line)
    {
        if(strpos($line, "|") !== 1) return;

        $line = trim(str_replace('|', '', $line));

        $parts = explode('=', $line);

        if(count($parts) != 2) return;


        switch(trim($parts[0]))
        {
            case Npc::NAME:
                $this->name=trim($parts[1]);
                break;
            case Npc::RACE:
                $this->race=trim($parts[1]);
                break;
            case Npc::TITLE:
                $this->title=trim($parts[1]);
                break;
            case Npc::LOCATION:
                $this->location->parseFromWikiString(trim($parts[1]));
                break;
        }
    }

    public function jsonSerialize()
    {
        return [
            'Name'=>$this->name,
            'Coordinates'=>$this->location,
            'Description'=>$this->title,
            'Inventory'=>$this->inventory
        ];
    }

}