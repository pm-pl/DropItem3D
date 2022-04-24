<?php

declare(strict_types=1);

namespace boymelancholy\di3d;

use boymelancholy\di3d\entity\object\RealisticDropItem;
use boymelancholy\di3d\listener\Di3dListener;
use boymelancholy\di3d\listener\drop\DropItemListener;
use boymelancholy\di3d\listener\pickup\InteractPickUpListener;
use boymelancholy\di3d\listener\pickup\LikeVanillaPickUpListener;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\World;

class DropItem3D extends PluginBase
{
    /** @var Config */
    private Config $config;

    public function onEnable() : void
    {
        self::$instance = $this;

        $this->registerConfig();
        $this->registerObject();
        $this->registerListener();
    }

    public function getConfig() : Config
    {
        return $this->config;
    }

    private function registerListener()
    {
        $listeners = [];
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

    private function registerConfig()
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            @mkdir($this->getDataFolder());
            $this->saveResource("config.yml");
        }
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }


    private static self $instance;

    public static function getInstance() : self
    {
        return self::$instance;
    }
}