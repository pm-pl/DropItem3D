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

namespace boymelancholy\di3d\listener\pickup;

use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\event\Di3dPickUpItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

class LikeVanillaPickUpListener implements Listener
{
    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $nearestRealisticDropItem = $player->getWorld()->getNearestEntity(
            $player->getLocation(),
            2.0,
            RealisticDropItem::class
        );

        if (!$nearestRealisticDropItem instanceof RealisticDropItem) return;
        $item = $nearestRealisticDropItem->getItem();

        if ($item === null) {
            $nearestRealisticDropItem->flagForDespawn();
            return;
        }

        (new Di3dPickUpItemEvent($player, $item, $nearestRealisticDropItem))->call();
    }
}