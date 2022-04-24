<?php

declare(strict_types=1);

namespace boymelancholy\di3d\listener\drop;

use boymelancholy\di3d\Di3dConstants;
use boymelancholy\di3d\DropItem3D;
use boymelancholy\di3d\entity\object\RealisticDropItem;
use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\item\Armor;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;

class DropItemListener implements Listener
{
    public function onDrop(EntitySpawnEvent $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof ItemEntity) return;

        $equip = false;
        $item = clone $entity->getItem();
        if ($item instanceof Armor) {
            $style = (int) DropItem3D::getInstance()->getConfig()->get(Di3dConstants::CONFIG_ARMOR_DROP_STYLE);
            $equip = $style == 0;
        }

        DropItem3D::getInstance()->getScheduler()->scheduleRepeatingTask(
            new ClosureTask(function () use($entity, $item, $equip)
            {
                if ($entity->isOnGround()) {
                    $rdi = new RealisticDropItem($entity->getLocation());
                    $rdi->spawnToAll();

                    if ($equip) {
                        /** @var Armor $item */
                        $rdi->setEquippableItem($item);
                    } else {
                        $rdi->setHeldItem($item);
                    }

                    $entity->flagForDespawn();
                    throw new CancelTaskException;
                }
            }),
            1
        );
    }
}