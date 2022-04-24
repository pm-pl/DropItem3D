# DropItem3D
![PHPVersion](https://img.shields.io/badge/PHP-v8.0-blue?style=flat-square)  
![License](https://img.shields.io/github/license/boymelancholy/DropItem3D?color=green&style=flat-square)  
![PocketMine API](https://img.shields.io/badge/PMMP%20API-v4.0.0-orange?style=flat-square)  
![GitHub last commit](https://img.shields.io/github/last-commit/boymelancholy/DropItem3D?color=purple&style=flat-square)  
![overview](assets/di3d_overview.png)  
<span style="font-size:17px">PocketMine-MP plugin to be able to see drop items realistic.</span>  
　

## Configuration
```yaml
# How to pick up drop item.
## 0 : Like vanilla. (If you approach to item, you can pick up it)
## 1 : Click then pick up. (PC: Right click, Mobile: Tap)
drop-item-pickup: 0

# Drop armor item style.
## 0 : Like be equipping it.
## 1 : Like an item.
armor-drop-style: 0

# If you pick up an armor, what if it does?
## 0 : Equip it instantly.
## 1 : Add it to inventory.
armor-equip-instantly: 0
```  
　  
## Demo view
<img src="assets/pickup_like_vanilla.gif" width="395" height="200" alt="" /> <img src="assets/pickup_click.gif" width="395" height="200" alt="" />  

First demo = `drop-item-pickup: 0`  
Last demo = `drop-item-pickup: 1`
  
　  
## Todo
- [ ] To be able to retrieve the exact coordinates by click.
