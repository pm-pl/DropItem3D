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

namespace boymelancholy\di3d\listener\drop;

use boymelancholy\di3d\Di3dConstants;
use boymelancholy\di3d\DropItem3D;
use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\event\Di3dDropItemEvent;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\event\Listener;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;

class DropItemListener implements Listener
{
    public function onDropItem(ItemSpawnEvent $event)
    {
        $entity = $event->getEntity();
        $item = clone $entity->getItem();
        $ids = (array) DropItem3D::getInstance()->getConfig()->get(Di3dConstants::CONFIG_ITEM_ID_BLACK_LIST);
        if (!empty($ids)) {
            $blackList = array_map(function (mixed $id) { return is_int($id) ? $id . ":0" : (string) $id; }, $ids);
            if (in_array($item->getId() . ":" . $item->getMeta(), $blackList)) return;
        }

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