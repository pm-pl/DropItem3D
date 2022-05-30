# DropItem3D
|PHP|License|PMMP API|Commit|Stars|Issues|
|:---:|:---:|:---:|:---:|:---:|:---:|
| ![PHPVersion](https://img.shields.io/badge/PHP-v8.0-blue?style=flat-square) | ![License](https://img.shields.io/github/license/boymelancholy/DropItem3D?color=green&style=flat-square) | ![PocketMine API](https://img.shields.io/badge/PMMP%20API-v4.0.0-orange?style=flat-square) | ![GitHub last commit](https://img.shields.io/github/last-commit/boymelancholy/DropItem3D?color=purple&style=flat-square) | ![stars](https://img.shields.io/github/stars/boymelancholy/DropItem3D?color=yellow&style=flat-square) | [![GitHub issues](https://img.shields.io/github/issues/boymelancholy/DropItem3D?color=red&style=flat-square)](https://github.com/boymelancholy/DropItem3D/issues)

|Poggit Status|Total Download|
|:---:|:---:|
| [![State](https://poggit.pmmp.io/shield.state/DropItem3D?style=flat-square)](https://poggit.pmmp.io/p/DropItem3D) | [![Downloads Total](https://poggit.pmmp.io/shield.dl.total/DropItem3D?style=flat-square)](https://poggit.pmmp.io/p/DropItem3D) |

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

# If you have any items not want to convert 3D object, add it.
# Only ID or "ID:META" notation is supported.
# If you write in "ID:META" format, do not forget the double quotation marks, like "35:14".
item-id-black-list: []
```
  
　  
## Todo
- [ ] To be able to retrieve the exact coordinates by click.
