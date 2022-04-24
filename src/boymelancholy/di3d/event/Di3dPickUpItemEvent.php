<?php

declare(strict_types=1);

namespace boymelancholy\di3d\event;

use pocketmine\event\player\PlayerEvent;
use pocketmine\item\Item;
use pocketmine\player\Player;

class Di3DPickUpItemEvent extends PlayerEvent
{
    /** @var Item */
    private Item $item;

    public function __construct(Player $player, Item $armor)
    {
        $this->player = $player;
        $this->item = $armor;
    }

    /**
     * Item that you picked up.
     * @return Item
     */
    public function getItem() : Item
    {
        return $this->item;
    }
}