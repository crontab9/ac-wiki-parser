<?php

namespace AcWikiParser\Commands;

use AcWikiParser\Exceptions\InvalidNpcException;
use AcWikiParser\Npc;
use AcWikiParser\XPathLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NpcParser extends Command {


    protected function configure()
    {
        $this->setName('npc-parser')
            ->setDescription('Will parse the wiki XML file for all NPCs, their location and inventory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xpath=XPathLoader::getDomXPath(__DIR__."/../../wiki/wiki.xml");

        $result=$xpath->query('//page/revision/text[contains(.,\'{{NPC\')]');

        $npcs=[];

        /** @var \DOMNode $element */
        foreach($result as $element)
        {
            try {
                $npc = new Npc();
                $npc->parseFromWikiNode($element);

                $npcs[]=$npc;
            }catch(InvalidNpcException $e){
                continue;
            }

        }

        file_put_contents(__DIR__."/../../wiki/out.json", json_encode($npcs, JSON_PRETTY_PRINT));

    }

}