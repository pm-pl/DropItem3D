<?php

declare(strict_types=1);

namespace boymelancholy\di3d\listener\pickup;

use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\event\Di3DPickUpItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEntityInteractEvent;

class InteractPickUpListener implements Listener
{
    public function onEntityInteract(PlayerEntityInteractEvent $event)
    {
        $player = $event->getPlayer();
        $entity = $event->getEntity();
        if (!$entity instanceof RealisticDropItem) return;

        $item = $entity->getItem();

        if ($item === null) {
            $entity->flagForDespawn();
            return;
        }

        (new Di3DPickUpItemEvent($player, $item, $entity))->call();
    }
}