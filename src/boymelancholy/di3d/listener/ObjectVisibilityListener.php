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
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\TieredTool;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\World;

class ObjectVisibilityListener implements Listener
{
    /**
     * @param PlayerJoinEvent $event
     * @priority LOWEST
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $world = $event->getPlayer()->getWorld();
        DropItem3D::getInstance()->getScheduler()->scheduleDelayedTask(
            new ClosureTask(function () use($world) {
                $this->loadRealisticDropItems($world);
            }),
            20
        );
    }

    /**
     * @param EntityTeleportEvent $event
     * @priority LOWEST
     */
    public function onTeleport(EntityTeleportEvent $event)
    {
        $from = $event->getFrom();
        $to = $event->getTo();
        if ($from->getWorld() === $to->getWorld()) return;
        $world = $to->getWorld();
        DropItem3D::getInstance()->getScheduler()->scheduleDelayedTask(
            new ClosureTask(function () use($world) {
                $this->loadRealisticDropItems($world);
            }),
            20
        );
    }

    /**
     * Load RealisticDropItem in specific world
     * @param World $world
     */
    private function loadRealisticDropItems(World $world)
    {
        /** @var array<RealisticDropItem> $allRdi */
        $allRdi = [];
        foreach ($world->getEntities() as $entity) {
            if ($entity instanceof RealisticDropItem) {
                $allRdi[] = $entity;
            }
        }
        foreach ($allRdi as $rdi) {
            $item = $rdi->getItem();
            if ($item === null) {
                $rdi->flagForDespawn();
                continue;
            }
            if (!in_array($item, $rdi->getArmorInventory()->getContents())) {
                $isRodShape = in_array($item->getId(), Di3dConstants::ITEM_ROD_SHAPED);
                if ($isRodShape || $item instanceof TieredTool) {
                    $rdi->setRodShapeItem($item, true);
                } else {
                    $rdi->setHeldItem($item, true);
                }
            }
        }
    }
}