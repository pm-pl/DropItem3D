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

namespace boymelancholy\di3d\listener;

use boymelancholy\di3d\Di3dConstants;
use boymelancholy\di3d\DropItem3D;
use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\event\Di3dDropItemEvent;
use boymelancholy\di3d\event\Di3dPickUpItemEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\Listener;
use pocketmine\item\Armor;
use pocketmine\item\Skull;
use pocketmine\item\TieredTool;
use pocketmine\world\sound\PopSound;

class Di3dListener implements Listener
{
    public function onPickUp(Di3dPickUpItemEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $entity = $event->getRealisticDropItem();

        $equip = (int) DropItem3D::getInstance()->getConfig()->get(Di3dConstants::CONFIG_ARMOR_EQUIP_INSTANTLY);

        $willClose = false;
        switch (true) {
            case $item instanceof Armor && $equip === Di3dConstants::PUT_AWAY_ARMOR_TO_EQUIP:
                if (!$player->getArmorInventory()->getItem($item->getArmorSlot()) instanceof Armor) {
                    $player->getArmorInventory()->setItem($item->getArmorSlot(), $item);
                    $willClose = true;
                } else {
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                        $willClose = true;
                    }
                }
                break;

            case !$item instanceof Armor:
            case $equip === Di3dConstants::PUT_AWAY_ARMOR_TO_INVENTORY:
                if ($player->getInventory()->canAddItem($item)) {
                    $player->getInventory()->addItem($item);
                    $willClose = true;
                }
                break;
        }

        if ($willClose) {
            $player->getWorld()->addSound($player->getPosition(), new PopSound());
            $ev = new EntityItemPickupEvent($player, $entity, $item, $player->getInventory());
            if($player->hasFiniteResources() && $player->getInventory() === null){
                $ev->cancel();
            }
            $ev->call();
            $entity->flagForDespawn();
        }
    }

    public function onDrop(Di3dDropItemEvent $event)
    {
        $item = $event->getItem();
        $rdi = $event->getRealisticDropItem();

        $style = (int) DropItem3D::getInstance()->getConfig()->get(Di3dConstants::CONFIG_ARMOR_DROP_STYLE);
        $isRodShape = in_array($item->getId(), Di3dConstants::ITEM_ROD_SHAPED);

        switch (true) {
            case $item instanceof Armor && $style === Di3dConstants::DROP_STYLE_ARMOR:
                $rdi->setEquitableItem($item);
                return;

            case $item instanceof Skull && $style === Di3dConstants::DROP_STYLE_ARMOR:
                $rdi->setSkullItem($item);
                return;

            case $isRodShape || $item instanceof TieredTool:
                $rdi->setRodShapeItem($item);
                return;

            case $style === Di3dConstants::DROP_STYLE_ITEM:
                $rdi->setHeldItem($item);
                return;
        }

        $rdi->setHeldItem($item);
    }

    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof RealisticDropItem) return;
        $cause = $event->getCause();
        $killable = [
            $event::CAUSE_FIRE,
            $event::CAUSE_FIRE_TICK,
            $event::CAUSE_LAVA,
            $event::CAUSE_VOID
        ];
        if (!in_array($cause, $killable)) {
            $event->cancel();
            return;
        }
        $entity->flagForDespawn();
    }
}