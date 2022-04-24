<?php

declare(strict_types=1);

namespace boymelancholy\di3d\entity\object;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\Skull;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class RealisticDropItem extends Living
{
    /** @var Item|null */
    private ?Item $item = null;

    protected function getInitialSizeInfo() : EntitySizeInfo
    {
        return new EntitySizeInfo(1.8, 0.6, 1.62);
    }

    public static function getNetworkTypeId() : string
    {
        return EntityIds::ARMOR_STAND;
    }

    public function getName() : string
    {
        return "RealisticDropItem";
    }

    public function initEntity(CompoundTag $nbt) : void
    {
        parent::initEntity($nbt);

        $this->setHasGravity(false);
        $this->setInvisible();
        $this->setImmobile();
    }

    /**
     * Get an object's item.
     * @return Item|null
     */
    public function getItem() : ?Item
    {
        return $this->item;
    }

    /**
     * Make object have the armor.
     * @param Armor $item
     */
    public function setEquitableItem(Armor $item)
    {
        $this->item = $item;
        $this->getArmorInventory()->setItem($item->getArmorSlot(), $item);

        $deltaVector = Vector3::zero();
        $deltaVector->y += match ($item->getArmorSlot()) {
            ArmorInventory::SLOT_HEAD => -1.7,
            ArmorInventory::SLOT_CHEST => -0.75,
            ArmorInventory::SLOT_LEGS => -0.3,
            ArmorInventory::SLOT_FEET => 0.1,
        };
        $yaw = mt_rand(0, 360);
        $pitch = 0.0;
        $this->teleport($this->getLocation()->addVector($deltaVector), $yaw, $pitch);

        $this->scheduleUpdate();
    }

    /**
     * Make object have the item.
     * @param Item $item
     */
    public function setHeldItem(Item $item)
    {
        $this->item = $item;

        $this->sendEquipPacket($item);

        $deltaVector = Vector3::zero();
        $deltaVector->y -= 0.71;
        $yaw = mt_rand(0, 360);
        $pitch = 0.0;
        $this->teleport($this->getLocation()->addVector($deltaVector), $yaw, $pitch);

        $this->scheduleUpdate();
    }

    /**
     * Make object have the skull item.
     * @param Skull $item
     */
    public function setSkullItem(Skull $item)
    {
        $this->item = $item;

        $this->getArmorInventory()->setItem(ArmorInventory::SLOT_HEAD, $item);

        $deltaVector = Vector3::zero();
        $deltaVector->y -= 1.7;
        $yaw = mt_rand(0, 360);
        $pitch = 0.0;
        $this->teleport($this->getLocation()->addVector($deltaVector), $yaw, $pitch);

        $this->scheduleUpdate();
    }

    /**
     * Make object have the item whose shape is rod.
     * @param Item $item
     */
    public function setRodShapeItem(Item $item)
    {
        $this->item = $item;
        $this->getNetworkProperties()->setInt(EntityMetadataProperties::ARMOR_STAND_POSE_INDEX, 8);

        $this->sendEquipPacket($item);

        $deltaVector = Vector3::zero();
        $deltaVector->y -= 0.71;
        $yaw = mt_rand(0, 360);
        $pitch = 0.0;
        $this->teleport($this->getLocation()->addVector($deltaVector), $yaw, $pitch);

        $this->scheduleUpdate();
    }

    /**
     * Send MobEquipmentPacket
     * @param Item $item
     */
    private function sendEquipPacket(Item $item)
    {
        $pk = new MobEquipmentPacket();
        $pk->actorRuntimeId = $this->getId();
        $pk->item = ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($item));
        $pk->hotbarSlot = 0;
        $pk->inventorySlot = 0;
        foreach ($this->getWorld()->getPlayers() as $player) {
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }
}