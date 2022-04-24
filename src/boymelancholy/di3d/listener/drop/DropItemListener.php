<?php

declare(strict_types=1);

namespace boymelancholy\di3d\listener\drop;

use boymelancholy\di3d\DropItem3D;
use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\event\Di3dDropItemEvent;
use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;

class DropItemListener implements Listener
{
    public function onDrop(EntitySpawnEvent $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof ItemEntity) return;

        $item = clone $entity->getItem();
        DropItem3D::getInstance()->getScheduler()->scheduleRepeatingTask(
            new ClosureTask(function () use($entity, $item)
            {
                if ($entity->isOnGround()) {
                    $rdi = new RealisticDropItem($entity->getLocation());
                    $rdi->spawnToAll();

                    (new Di3dDropItemEvent($item, $rdi))->call();

                    $entity->flagForDespawn();
                    throw new CancelTaskException;
                }
            }),
            1
        );
    }
}