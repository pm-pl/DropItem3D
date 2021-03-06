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

use pocketmine\item\ItemIds;

class Di3dConstants
{
    const CONFIG_DROP_ITEM_PICKUP = "drop-item-pickup";
    const CONFIG_ARMOR_DROP_STYLE = "armor-drop-style";
    const CONFIG_ARMOR_EQUIP_INSTANTLY = "armor-equip-instantly";
    const CONFIG_ITEM_ID_BLACK_LIST = "item-id-black-list";

    const PICK_UP_LIKE_VANILLA = 0;
    const PICK_UP_BY_INTERACT = 1;

    const DROP_STYLE_ARMOR = 0;
    const DROP_STYLE_ITEM = 1;

    const PUT_AWAY_ARMOR_TO_EQUIP = 0;
    const PUT_AWAY_ARMOR_TO_INVENTORY = 1;

    const TAG_EQUIPPING_ITEM = "di3d_equipping_item";
    const ITEM_ROD_SHAPED = [
        ItemIds::STICK,
        ItemIds::BONE,
        ItemIds::TRIDENT,
        ItemIds::BAMBOO,
        ItemIds::BOW,
        ItemIds::FISHING_ROD,
        ItemIds::BLAZE_ROD,
        625, // Spyglass
    ];

    const NETHERITE_ITEM_ID = [
        742, // Ingot
        743, // Sword
        744, // Shovel
        745, // Pickaxe
        746, // Axe
        747, // Hoe
        748, // Helmet
        749, // Chestplate
        750, // Leggings
        751, // Boots
        752, // Scrap
    ];
}