<?php

declare(strict_types=1);

namespace boymelancholy\di3d;

class Di3dConstants
{
    const CONFIG_DROP_ITEM_PICKUP = "drop-item-pickup";
    const CONFIG_ARMOR_DROP_STYLE = "armor-drop-style";
    const CONFIG_ARMOR_EQUIP_INSTANTLY = "armor-equip-instantly";

    const PICK_UP_LIKE_VANILLA = 0;
    const PICK_UP_BY_INTERACT = 1;

    const DROP_STYLE_ARMOR = 0;
    const DROP_STYLE_ITEM = 1;

    const PUT_AWAY_ARMOR_TO_EQUIP = 0;
    const PUT_AWAY_ARMOR_TO_INVENTORY = 1;
}