<?php

declare(strict_types=1);

namespace boymelancholy\di3d\listener;

use boymelancholy\di3d\Di3dConstants;
use boymelancholy\di3d\entity\object\RealisticDropItem;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\TieredTool;
use pocketmine\world\World;

class ObjectVisiblityListener implements Listener
{
    /**
     * @param PlayerJoinEvent $event
     * @priority LOWEST
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $this->loadRealisticDropItems($event->getPlayer()->getWorld());
    }

    /**
     * @param EntityTeleportEvent $event
     * @priority LOWEST
     */
    public function onTeleport(EntityTeleportEvent $event)
    {
        $this->loadRealisticDropItems($event->getEntity()->getWorld());
    }

    /**
     * Load RealisticDropItem in specific world
     * @param World $world
     */
    private function loadRealisticDropItems(World $world)
    {
        /** @var array<RealisticDropItem> $allRdi */
        $allRdi = array_filter($world->getEntities(), function ($e) { return $e instanceof RealisticDropItem; });
        foreach ($allRdi as $rdi) {
            if (empty($rdi->getArmorInventory()->getContents())) {
                $item = $rdi->getItem();
                $isRodShape = in_array($item->getId(), Di3dConstants::ITEM_ROD_SHAPED);
                if ($isRodShape || $item instanceof TieredTool) {
                    $rdi->setRodShapeItem($item);
                } else {
                    $rdi->setHeldItem($item);
                }
            }
        }
    }
}