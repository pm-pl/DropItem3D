<?php

declare(strict_types=1);

namespace boymelancholy\di3d\event;

use boymelancholy\di3d\entity\object\RealisticDropItem;
use pocketmine\event\player\PlayerEvent;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\player\Player;

class Di3dDropItemEvent extends PlayerEvent
{
    /** @var Item */
    private Item $item;

    /** @var RealisticDropItem */
    private RealisticDropItem $realisticDropItem;

    public function __construct(Player $player, Item $item, RealisticDropItem $realisticDropItem)
    {
        $this->player = $player;
        $this->item = $item;
        $this->realisticDropItem = $realisticDropItem;
    }

    /**
     * Item that you drop.
     * @return Item
     */
    public function getItem() : Item
    {
        return $this->item;
    }

    /**
     * Drop item object
     * @return RealisticDropItem
     */
    public function getRealisticDropItem() : RealisticDropItem
    {
        return $this->realisticDropItem;
    }
}