<?php

declare(strict_types=1);

namespace boymelancholy\di3d\listener;

use boymelancholy\di3d\entity\object\RealisticDropItem;
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
        if (!$player->getInventory()->canAddItem($item)) return;

        $player->getInventory()->addItem($item);
        $entity->flagForDespawn();
    }
}