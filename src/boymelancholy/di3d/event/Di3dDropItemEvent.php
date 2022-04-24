<?php
/**
 *
 * ██████╗ ██████╗  ██████╗ ██████╗ ██╗████████╗███████╗███╗   ███╗██████╗ ██████╗
 * ██╔══██╗██╔══██╗██╔═══██╗██╔══██╗██║╚══██╔══╝██╔════╝████╗ ████║╚════██╗██╔══██╗
 * ██║  ██║██████╔╝██║   ██║██████╔╝██║   ██║   █████╗  ██╔████╔██║ █████╔╝██║  ██║
 * ██║  ██║██╔══██╗██║   ██║██╔═══╝ ██║   ██║   ██╔══╝  ██║╚██╔╝██║ ╚═══██╗██║  ██║
 * ██████╔╝██║  ██║╚██████╔╝██║     ██║   ██║   ███████╗██║ ╚═╝ ██║██████╔╝██████╔╝
 * ╚═════╝ ╚═╝  ╚═╝ ╚═════╝ ╚═╝     ╚═╝   ╚═╝   ╚══════╝╚═╝     ╚═╝╚═════╝ ╚═════╝
 *
 * Your minecraft server will make more REALISTIC!
 *
 * @author boymelancholy
 * @link https://github.com/boymelancholy/DropItem3D/
 *
 */
declare(strict_types=1);

namespace boymelancholy\di3d\event;

use boymelancholy\di3d\entity\object\RealisticDropItem;
use pocketmine\event\Event;
use pocketmine\item\Item;

class Di3dDropItemEvent extends Event
{
    /** @var Item */
    private Item $item;

    /** @var RealisticDropItem */
    private RealisticDropItem $realisticDropItem;

    public function __construct(Item $item, RealisticDropItem $realisticDropItem)
    {
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