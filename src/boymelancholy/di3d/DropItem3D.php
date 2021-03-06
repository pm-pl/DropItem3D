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

namespace boymelancholy\di3d;

use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\listener\Di3dListener;
use boymelancholy\di3d\listener\drop\DropItemListener;
use boymelancholy\di3d\listener\ObjectVisibilityListener;
use boymelancholy\di3d\listener\pickup\InteractPickUpListener;
use boymelancholy\di3d\listener\pickup\LikeVanillaPickUpListener;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class DropItem3D extends PluginBase
{
    public function onEnable() : void
    {
        self::$instance = $this;

        $this->registerObject();
        $this->registerListener();
    }

    private function registerListener()
    {
        $listeners = [];
        $listeners[] = new ObjectVisibilityListener();
        $listeners[] = new DropItemListener();
        $listeners[] = match ((int) $this->getConfig()->get(Di3dConstants::CONFIG_DROP_ITEM_PICKUP)) {
            Di3dConstants::PICK_UP_LIKE_VANILLA => new LikeVanillaPickUpListener(),
            Di3dConstants::PICK_UP_BY_INTERACT => new InteractPickUpListener()
        };
        $listeners[] = new Di3dListener();

        foreach ($listeners as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $this);
        }
    }

    private function registerObject()
    {
        EntityFactory::getInstance()->register(
            RealisticDropItem::class,
            function(World $world, CompoundTag $nbt) : RealisticDropItem {
                return new RealisticDropItem(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
            },
            ["RealisticDropItem", "minecraft:armor_stand"],
            EntityLegacyIds::ARMOR_STAND
        );
    }

    /** @var self */
    private static self $instance;

    /**
     * DropItem3D instance (PluginBase)
     * @return self
     */
    public static function getInstance() : self
    {
        return self::$instance;
    }
}