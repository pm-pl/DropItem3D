<?php

declare(strict_types=1);

namespace boymelancholy\di3d;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class DropItem3D extends PluginBase
{
    public function onEnable() : void
    {

    }

    public function onDisable() : void
    {

    }

    private function registerConfig() : void
    {
        @mkdir($this->getDataFolder());
        $this->saveResource('config.yml');
        $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
    }
}