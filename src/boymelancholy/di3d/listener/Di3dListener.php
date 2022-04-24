<?php

declare(strict_types=1);

namespace boymelancholy\di3d\listener;

use boymelancholy\di3d\Di3dConstants;
use boymelancholy\di3d\DropItem3D;
use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\event\Di3dDropItemEvent;
use boymelancholy\di3d\event\Di3DPickUpItemEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\Armor;
use pocketmine\item\VanillaItems;

class Di3dListener implements Listener
{
    public function onPickUp(Di3DPickUpItemEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        $equip = (int) DropItem3D::getInstance()->getConfig()->get(Di3dConstants::CONFIG_ARMOR_EQUIP_INSTANTLY);

        switch (true) {
            case !$item instanceof Armor:
            case $equip === Di3dConstants::PUT_AWAY_ARMOR_TO_INVENTORY:
                if (!$player->getInventory()->canAddItem($item)) return;
                $player->getInventory()->addItem($item);
                break;

            case $item instanceof Armor && Di3dConstants::PUT_AWAY_ARMOR_TO_EQUIP:
                if ($player->getArmorInventory()->getChestplate() === VanillaItems::AIR()) {
                    $player->getArmorInventory()->setItem($item->getArmorSlot(), $item);
                } else {
                    if (!$player->getInventory()->canAddItem($item)) return;
                    $player->getInventory()->addItem($item);
                }
                break;
        }
    }

    public function onDrop(Di3dDropItemEvent $event)
    {
        $item = $event->getItem();
        $rdi = $event->getRealisticDropItem();

        $equip = Di3dConstants::DROP_STYLE_ITEM;
        if ($item instanceof Armor) {
            $style = (int) DropItem3D::getInstance()->getConfig()->get(Di3dConstants::CONFIG_ARMOR_DROP_STYLE);
            $equip = $style === Di3dConstants::DROP_STYLE_ARMOR;
        }

        if ($equip) {
            /** @var Armor $item */
            $rdi->setEquitableItem($item);
            return;
        }

        $rdi->setHeldItem($item);
    }

    public function onChoking(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof RealisticDropItem) return;

        if ($event->getCause() === $event::CAUSE_SUFFOCATION) {
            $event->cancel();
        }
    }
}