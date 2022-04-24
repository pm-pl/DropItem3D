<?php

declare(strict_types=1);

namespace boymelancholy\di3d\entity\object;

use boymelancholy\di3d\Di3dConstants;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\Skull;
use pocketmine\math\Vector3;
use pocketmine\nbt\NbtDataException;
use pocketmine\nbt\NoSuchTagException;
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

        try {
            $jsonData = $nbt->getString(Di3dConstants::TAG_EQUIPPING_ITEM);
            $this->item = Item::jsonDeserialize(json_decode($jsonData, flags: JSON_OBJECT_AS_ARRAY));
        } catch (NoSuchTagException|NbtDataException $e) {
            $this->item = null;
        }
    }

    public function saveNBT() : CompoundTag
    {
        $nbt = parent::saveNBT();
        if ($this->item === null) {
            return $nbt;
        }

        $jsonData = json_encode($this->item->jsonSerialize());
        $nbt->setString(Di3dConstants::TAG_EQUIPPING_ITEM, $jsonData);
        return $nbt;
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
        $this->saveNBT();
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
        $this->saveNBT();
    }

    /**
     * Make object have the item.
     * @param Item $item
     * @param bool $load
     */
    public function setHeldItem(Item $item, bool $load = false)
    {
        $this->item = $item;
        $deltaVector = Vector3::zero();
        $deltaVector->y -= $load ? 0 : 0.65;
        $yaw = mt_rand(0, 360);
        $pitch = 0.0;
        $this->teleport($this->getLocation()->addVector($deltaVector), $yaw, $pitch);

        $this->sendMobEquipPacket();
        $this->saveNBT();
    }

    /**
     * Make object have the item whose shape is rod.
     * @param Item $item
     * @param bool $load
     */
    public function setRodShapeItem(Item $item, bool $load = false)
    {
        $this->item = $item;

        $this->getNetworkProperties()->setInt(EntityMetadataProperties::ARMOR_STAND_POSE_INDEX, 8);

        $deltaVector = Vector3::zero();
        $deltaVector->y -= $load ? 0 : 1.35;
        $yaw = mt_rand(0, 360);
        $pitch = 0.0;
        $this->teleport($this->getLocation()->addVector($deltaVector), $yaw, $pitch);

        $this->sendMobEquipPacket();
        $this->saveNBT();
    }

    private function sendMobEquipPacket()
    {
        $pk = new MobEquipmentPacket();
        $pk->actorRuntimeId = $this->getId();
        $pk->item = ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($this->item));
        $pk->hotbarSlot = 0;
        $pk->inventorySlot = 0;

        foreach ($this->getWorld()->getPlayers() as $player) {
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }
}