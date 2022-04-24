<?php

declare(strict_types=1);

namespace boymelancholy\di3d;

use boymelancholy\di3d\entity\object\RealisticDropItem;
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
        @mkdir($this->getDataFolder());
        $this->saveResource('config.yml');
        $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
    }


    private static self $instance;

    public static function getInstance() : self
    {
        return self::$instance;
    }
}