<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\utils\DyeColor;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Living;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\tile\Skull;
use function constant;
use function defined;
use function explode;
use function is_a;
use function is_numeric;
use function str_replace;
use function strtoupper;
use function trim;

/**
 * Manages Item instance creation and registration
 */
class ItemFactory{

	/** @var \SplFixedArray */
	private static $list = [];

	/** @var Item|null */
	private static $air = null;

	public static function init(){
		self::$list = []; //in case of re-initializing

		self::registerItem(new Shovel(Item::IRON_SHOVEL, "Iron Shovel", TieredTool::TIER_IRON));
		self::registerItem(new Pickaxe(Item::IRON_PICKAXE, "Iron Pickaxe", TieredTool::TIER_IRON));
		self::registerItem(new Axe(Item::IRON_AXE, "Iron Axe", TieredTool::TIER_IRON));
		self::registerItem(new FlintSteel());
		self::registerItem(new Apple());
		self::registerItem(new Bow());
		self::registerItem(new Arrow());
		self::registerItem(new Coal(Item::COAL, 0, "Coal"));
		self::registerItem(new Coal(Item::COAL, 1, "Charcoal"));
		self::registerItem(new Item(Item::DIAMOND, 0, "Diamond"));
		self::registerItem(new Item(Item::IRON_INGOT, 0, "Iron Ingot"));
		self::registerItem(new Item(Item::GOLD_INGOT, 0, "Gold Ingot"));
		self::registerItem(new Sword(Item::IRON_SWORD, "Iron Sword", TieredTool::TIER_IRON));
		self::registerItem(new Sword(Item::WOODEN_SWORD, "Wooden Sword", TieredTool::TIER_WOODEN));
		self::registerItem(new Shovel(Item::WOODEN_SHOVEL, "Wooden Shovel", TieredTool::TIER_WOODEN));
		self::registerItem(new Pickaxe(Item::WOODEN_PICKAXE, "Wooden Pickaxe", TieredTool::TIER_WOODEN));
		self::registerItem(new Axe(Item::WOODEN_AXE, "Wooden Axe", TieredTool::TIER_WOODEN));
		self::registerItem(new Sword(Item::STONE_SWORD, "Stone Sword", TieredTool::TIER_STONE));
		self::registerItem(new Shovel(Item::STONE_SHOVEL, "Stone Shovel", TieredTool::TIER_STONE));
		self::registerItem(new Pickaxe(Item::STONE_PICKAXE, "Stone Pickaxe", TieredTool::TIER_STONE));
		self::registerItem(new Axe(Item::STONE_AXE, "Stone Axe", TieredTool::TIER_STONE));
		self::registerItem(new Sword(Item::DIAMOND_SWORD, "Diamond Sword", TieredTool::TIER_DIAMOND));
		self::registerItem(new Shovel(Item::DIAMOND_SHOVEL, "Diamond Shovel", TieredTool::TIER_DIAMOND));
		self::registerItem(new Pickaxe(Item::DIAMOND_PICKAXE, "Diamond Pickaxe", TieredTool::TIER_DIAMOND));
		self::registerItem(new Axe(Item::DIAMOND_AXE, "Diamond Axe", TieredTool::TIER_DIAMOND));
		self::registerItem(new Stick());
		self::registerItem(new Bowl());
		self::registerItem(new MushroomStew());
		self::registerItem(new Sword(Item::GOLDEN_SWORD, "Gold Sword", TieredTool::TIER_GOLD));
		self::registerItem(new Shovel(Item::GOLDEN_SHOVEL, "Gold Shovel", TieredTool::TIER_GOLD));
		self::registerItem(new Pickaxe(Item::GOLDEN_PICKAXE, "Gold Pickaxe", TieredTool::TIER_GOLD));
		self::registerItem(new Axe(Item::GOLDEN_AXE, "Gold Axe", TieredTool::TIER_GOLD));
		self::registerItem(new StringItem());
		self::registerItem(new Item(Item::FEATHER, 0, "Feather"));
		self::registerItem(new Item(Item::GUNPOWDER, 0, "Gunpowder"));
		self::registerItem(new Hoe(Item::WOODEN_HOE, "Wooden Hoe", TieredTool::TIER_WOODEN));
		self::registerItem(new Hoe(Item::STONE_HOE, "Stone Hoe", TieredTool::TIER_STONE));
		self::registerItem(new Hoe(Item::IRON_HOE, "Iron Hoe", TieredTool::TIER_IRON));
		self::registerItem(new Hoe(Item::DIAMOND_HOE, "Diamond Hoe", TieredTool::TIER_DIAMOND));
		self::registerItem(new Hoe(Item::GOLDEN_HOE, "Golden Hoe", TieredTool::TIER_GOLD));
		self::registerItem(new WheatSeeds());
		self::registerItem(new Item(Item::WHEAT, 0, "Wheat"));
		self::registerItem(new Bread());
		self::registerItem(new LeatherCap());
		self::registerItem(new LeatherTunic());
		self::registerItem(new LeatherPants());
		self::registerItem(new LeatherBoots());
		self::registerItem(new ChainHelmet());
		self::registerItem(new ChainChestplate());
		self::registerItem(new ChainLeggings());
		self::registerItem(new ChainBoots());
		self::registerItem(new IronHelmet());
		self::registerItem(new IronChestplate());
		self::registerItem(new IronLeggings());
		self::registerItem(new IronBoots());
		self::registerItem(new DiamondHelmet());
		self::registerItem(new DiamondChestplate());
		self::registerItem(new DiamondLeggings());
		self::registerItem(new DiamondBoots());
		self::registerItem(new GoldHelmet());
		self::registerItem(new GoldChestplate());
		self::registerItem(new GoldLeggings());
		self::registerItem(new GoldBoots());
		self::registerItem(new Item(Item::FLINT, 0, "Flint"));
		self::registerItem(new RawPorkchop());
		self::registerItem(new CookedPorkchop());
		self::registerItem(new PaintingItem());
		self::registerItem(new GoldenApple());
		self::registerItem(new Sign());
		self::registerItem(new ItemBlock(Block::OAK_DOOR_BLOCK, 0, Item::OAK_DOOR));

		//TODO: fix metadata for buckets with still liquid in them
		//the meta values are intentionally hardcoded because block IDs will change in the future
		self::registerItem(new Bucket(Item::BUCKET, 0, "Bucket"));
		self::registerItem(new MilkBucket(Item::BUCKET, 1, "Milk Bucket"));
		self::registerItem(new LiquidBucket(Item::BUCKET, 8, "Water Bucket", Block::FLOWING_WATER));
		self::registerItem(new LiquidBucket(Item::BUCKET, 10, "Lava Bucket", Block::FLOWING_LAVA));

		self::registerItem(new Minecart());
		self::registerItem(new Saddle());
		self::registerItem(new ItemBlock(Block::IRON_DOOR_BLOCK, 0, Item::IRON_DOOR));
		self::registerItem(new Redstone());
		self::registerItem(new Snowball());

		self::registerItem(new Boat());
		self::registerItem(new Item(Item::LEATHER, 0, "Leather"));


		self::registerItem(new Item(Item::BRICK, 0, "Brick"));
		self::registerItem(new Item(Item::CLAY_BALL, 0, "Clay"));
		self::registerItem(new ItemBlock(Block::SUGARCANE_BLOCK, 0, Item::SUGARCANE));
		self::registerItem(new Item(Item::PAPER, 0, "Paper"));
		self::registerItem(new Book());
		self::registerItem(new Item(Item::SLIME_BALL, 0, "Slimeball"));

		self::registerItem(new Egg());
		self::registerItem(new Compass());
		self::registerItem(new FishingRod());
		self::registerItem(new Clock());
		self::registerItem(new Item(Item::GLOWSTONE_DUST, 0, "Glowstone Dust"));
		self::registerItem(new RawFish());
		self::registerItem(new CookedFish());
		foreach(DyeColor::getAll() as $color){
			//TODO: use colour object directly
			//TODO: add interface to dye-colour objects
			//TODO: new dedicated dyes
			self::registerItem(new Dye($color->getInvertedMagicNumber(), $color->getDisplayName() . " Dye"));
			self::registerItem(new Bed($color->getMagicNumber(), $color->getDisplayName() . " Bed"));
			self::registerItem(new Banner($color->getInvertedMagicNumber(), $color->getDisplayName() . " Banner"));
		}
		self::registerItem(new Item(Item::BONE, 0, "Bone"));
		self::registerItem(new Item(Item::SUGAR, 0, "Sugar"));
		self::registerItem(new ItemBlock(Block::CAKE_BLOCK, 0, Item::CAKE));

		self::registerItem(new ItemBlock(Block::REPEATER_BLOCK, 0, Item::REPEATER));
		self::registerItem(new Cookie());
		self::registerItem(new FilledMap());
		self::registerItem(new Shears());
		self::registerItem(new Melon());
		self::registerItem(new PumpkinSeeds());
		self::registerItem(new MelonSeeds());
		self::registerItem(new RawBeef());
		self::registerItem(new Steak());
		self::registerItem(new RawChicken());
		self::registerItem(new CookedChicken());
		self::registerItem(new RottenFlesh());
		self::registerItem(new EnderPearl());
		self::registerItem(new BlazeRod());
		self::registerItem(new Item(Item::GHAST_TEAR, 0, "Ghast Tear"));
		self::registerItem(new Item(Item::GOLD_NUGGET, 0, "Gold Nugget"));
		self::registerItem(new ItemBlock(Block::NETHER_WART_PLANT, 0, Item::NETHER_WART));

		foreach(Potion::ALL as $type){
			self::registerItem(new Potion($type));
			self::registerItem(new SplashPotion($type));
		}
		self::registerItem(new GlassBottle());
		self::registerItem(new SpiderEye());
		self::registerItem(new Item(Item::FERMENTED_SPIDER_EYE, 0, "Fermented Spider Eye"));
		self::registerItem(new Item(Item::BLAZE_POWDER, 0, "Blaze Powder"));
		self::registerItem(new Item(Item::MAGMA_CREAM, 0, "Magma Cream"));
		self::registerItem(new ItemBlock(Block::BREWING_STAND_BLOCK, 0, Item::BREWING_STAND));
		self::registerItem(new ItemBlock(Block::CAULDRON_BLOCK, 0, Item::CAULDRON));

		self::registerItem(new Item(Item::GLISTERING_MELON, 0, "Glistering Melon"));

		foreach(EntityFactory::getKnownTypes() as $className){
			/** @var Living|string $className */
			if(is_a($className, Living::class, true) and $className::NETWORK_ID !== -1){
				self::registerItem(new SpawnEgg(Item::SPAWN_EGG, $className::NETWORK_ID, $className, "Spawn Egg"));
			}
		}

		self::registerItem(new ExperienceBottle());

		self::registerItem(new WritableBook());
		self::registerItem(new WrittenBook());
		self::registerItem(new Item(Item::EMERALD, 0, "Emerald"));
		self::registerItem(new ItemBlock(Block::ITEM_FRAME_BLOCK, 0, Item::ITEM_FRAME));
		self::registerItem(new ItemBlock(Block::FLOWER_POT_BLOCK, 0, Item::FLOWER_POT));
		self::registerItem(new Carrot());
		self::registerItem(new Potato());
		self::registerItem(new BakedPotato());
		self::registerItem(new PoisonousPotato());
		self::registerItem(new EmptyMap());
		self::registerItem(new GoldenCarrot());

		self::registerItem(new ItemBlock(Block::SKULL_BLOCK, Skull::TYPE_SKELETON, Item::SKULL));
		self::registerItem(new ItemBlock(Block::SKULL_BLOCK, Skull::TYPE_WITHER, Item::SKULL));
		self::registerItem(new ItemBlock(Block::SKULL_BLOCK, Skull::TYPE_ZOMBIE, Item::SKULL));
		self::registerItem(new ItemBlock(Block::SKULL_BLOCK, Skull::TYPE_HUMAN, Item::SKULL));
		self::registerItem(new ItemBlock(Block::SKULL_BLOCK, Skull::TYPE_CREEPER, Item::SKULL));
		self::registerItem(new ItemBlock(Block::SKULL_BLOCK, Skull::TYPE_DRAGON, Item::SKULL));

		self::registerItem(new Item(Item::NETHER_STAR, 0, "Nether Star"));
		self::registerItem(new PumpkinPie());
		self::registerItem(new Fireworks());
		self::registerItem(new Item(Item::FIREWORKSCHARGE, 0, "Firework Star"));
		self::registerItem(new EnchantedBook());
		self::registerItem(new ItemBlock(Block::COMPARATOR_BLOCK, 0, Item::COMPARATOR));
		self::registerItem(new Item(Item::NETHER_BRICK, 0, "Nether Brick"));
		self::registerItem(new Item(Item::NETHER_QUARTZ, 0, "Nether Quartz"));

		self::registerItem(new Item(Item::PRISMARINE_SHARD, 0, "Prismarine Shard"));
		self::registerItem(new ItemBlock(Block::HOPPER_BLOCK, 0, Item::HOPPER));
		self::registerItem(new RawRabbit());
		self::registerItem(new CookedRabbit());
		self::registerItem(new RabbitStew());
		self::registerItem(new Item(Item::RABBIT_FOOT, 0, "Rabbit's Foot"));
		self::registerItem(new Item(Item::RABBIT_HIDE, 0, "Rabbit Hide"));
		self::registerItem(new Item(Item::LEAD, 0, "Lead"));
		self::registerItem(new Item(Item::PRISMARINE_CRYSTALS, 0, "Prismarine Crystals"));
		self::registerItem(new RawMutton());
		self::registerItem(new CookedMutton());
		self::registerItem(new ArmorStand());
		self::registerItem(new ItemBlock(Block::SPRUCE_DOOR_BLOCK, 0, Item::SPRUCE_DOOR));
		self::registerItem(new ItemBlock(Block::BIRCH_DOOR_BLOCK, 0, Item::BIRCH_DOOR));
		self::registerItem(new ItemBlock(Block::JUNGLE_DOOR_BLOCK, 0, Item::JUNGLE_DOOR));
		self::registerItem(new ItemBlock(Block::ACACIA_DOOR_BLOCK, 0, Item::ACACIA_DOOR));
		self::registerItem(new ItemBlock(Block::DARK_OAK_DOOR_BLOCK, 0, Item::DARK_OAK_DOOR));
		self::registerItem(new ItemBlock(Item::BARRIER));
		self::registerItem(new ChorusFruit());
		self::registerItem(new Item(Item::CHORUS_FRUIT_POPPED, 0, "Popped Chorus Fruit"));

		self::registerItem(new Item(Item::DRAGON_BREATH, 0, "Dragon's Breath"));

		self::registerItem(new Elytra());
		self::registerItem(new Item(Item::SHULKER_SHELL, 0, "Shulker Shell"));

		self::registerItem(new Totem());

		self::registerItem(new Item(Item::BLEACH, 0, "Bleach")); //EDU
		self::registerItem(new Item(Item::IRON_NUGGET, 0, "Iron Nugget"));

		self::registerItem(new Beetroot());
		self::registerItem(new BeetrootSeeds());
		self::registerItem(new BeetrootSoup());
		self::registerItem(new RawSalmon());
		self::registerItem(new Clownfish());
		self::registerItem(new Pufferfish());
		self::registerItem(new CookedSalmon());

		self::registerItem(new DriedKelp());
		self::registerItem(new Item(Item::NAUTILUS_SHELL, 0, "Nautilus Shell"));
		self::registerItem(new GoldenAppleEnchanted());
		self::registerItem(new Item(Item::HEART_OF_THE_SEA, 0, "Heart of the Sea"));
		self::registerItem(new Item(Item::TURTLE_SHELL_PIECE, 0, "Scute"));

		self::registerItem(new Record(Item::RECORD_13, LevelSoundEventPacket::SOUND_RECORD_13));
		self::registerItem(new Record(Item::RECORD_CAT, LevelSoundEventPacket::SOUND_RECORD_CAT));
		self::registerItem(new Record(Item::RECORD_BLOCKS, LevelSoundEventPacket::SOUND_RECORD_BLOCKS));
		self::registerItem(new Record(Item::RECORD_CHIRP, LevelSoundEventPacket::SOUND_RECORD_CHIRP));
		self::registerItem(new Record(Item::RECORD_FAR, LevelSoundEventPacket::SOUND_RECORD_FAR));
		self::registerItem(new Record(Item::RECORD_MALL, LevelSoundEventPacket::SOUND_RECORD_MALL));
		self::registerItem(new Record(Item::RECORD_MELLOHI, LevelSoundEventPacket::SOUND_RECORD_MELLOHI));
		self::registerItem(new Record(Item::RECORD_STAL, LevelSoundEventPacket::SOUND_RECORD_STAL));
		self::registerItem(new Record(Item::RECORD_STRAD, LevelSoundEventPacket::SOUND_RECORD_STRAD));
		self::registerItem(new Record(Item::RECORD_WARD, LevelSoundEventPacket::SOUND_RECORD_WARD));
		self::registerItem(new Record(Item::RECORD_11, LevelSoundEventPacket::SOUND_RECORD_11));
		self::registerItem(new Record(Item::RECORD_WAIT, LevelSoundEventPacket::SOUND_RECORD_WAIT));

		//TODO: minecraft:acacia_sign
		//TODO: minecraft:balloon
		//TODO: minecraft:birch_sign
		//TODO: minecraft:carrotOnAStick
		//TODO: minecraft:chest_minecart
		//TODO: minecraft:command_block_minecart
		//TODO: minecraft:compound
		//TODO: minecraft:crossbow
		//TODO: minecraft:darkoak_sign
		//TODO: minecraft:end_crystal
		//TODO: minecraft:ender_eye
		//TODO: minecraft:fireball
		//TODO: minecraft:fireworksCharge
		//TODO: minecraft:glow_stick
		//TODO: minecraft:hopper_minecart
		//TODO: minecraft:horsearmordiamond
		//TODO: minecraft:horsearmorgold
		//TODO: minecraft:horsearmoriron
		//TODO: minecraft:horsearmorleather
		//TODO: minecraft:ice_bomb
		//TODO: minecraft:jungle_sign
		//TODO: minecraft:kelp
		//TODO: minecraft:lingering_potion
		//TODO: minecraft:medicine
		//TODO: minecraft:name_tag
		//TODO: minecraft:phantom_membrane
		//TODO: minecraft:rapid_fertilizer
		//TODO: minecraft:sparkler
		//TODO: minecraft:spawn_egg
		//TODO: minecraft:spruce_sign
		//TODO: minecraft:tnt_minecart
		//TODO: minecraft:trident
		//TODO: minecraft:turtle_helmet
	}

	/**
	 * Registers an item type into the index. Plugins may use this method to register new item types or override existing
	 * ones.
	 *
	 * NOTE: If you are registering a new item type, you will need to add it to the creative inventory yourself - it
	 * will not automatically appear there.
	 *
	 * @param Item $item
	 * @param bool $override
	 *
	 * @throws \RuntimeException if something attempted to override an already-registered item without specifying the
	 * $override parameter.
	 */
	public static function registerItem(Item $item, bool $override = false){
		$id = $item->getId();
		$variant = $item->getDamage();

		if(!$override and self::isRegistered($id, $variant)){
			throw new \RuntimeException("Trying to overwrite an already registered item");
		}

		self::$list[self::getListOffset($id, $variant)] = clone $item;
	}

	/**
	 * Returns an instance of the Item with the specified id, meta, count and NBT.
	 *
	 * @param int              $id
	 * @param int              $meta
	 * @param int              $count
	 * @param CompoundTag|null $tags
	 *
	 * @return Item
	 * @throws \InvalidArgumentException
	 */
	public static function get(int $id, int $meta = 0, int $count = 1, ?CompoundTag $tags = null) : Item{
		/** @var Item $item */
		$item = null;
		if($meta !== -1){
			if(isset(self::$list[$offset = self::getListOffset($id, $meta)])){
				$item = clone self::$list[$offset];
			}elseif(isset(self::$list[$zero = self::getListOffset($id, 0)]) and self::$list[$zero] instanceof Durable){
				/** @var Durable $item */
				$item = clone self::$list[$zero];
				$item->setDamage($meta);
			}elseif($id < 256){ //intentionally includes negatives, for extended block IDs
				$item = new ItemBlock($id, $meta);
			}
		}

		if($item === null){
			//negative damage values will fallthru to here, to avoid crazy shit with crafting wildcard hacks
			$item = new Item($id, $meta);
		}

		$item->setCount($count);
		if($tags !== null){
			$item->setNamedTag($tags);
		}
		return $item;
	}

	/**
	 * Tries to parse the specified string into Item types.
	 *
	 * Example accepted formats:
	 * - `diamond_pickaxe:5`
	 * - `minecraft:string`
	 * - `351:4 (lapis lazuli ID:meta)`
	 *
	 * @param string $str
	 *
	 * @return Item
	 *
	 * @throws \InvalidArgumentException if the given string cannot be parsed as an item identifier
	 */
	public static function fromString(string $str) : Item{
		$b = explode(":", str_replace([
			" ", "minecraft:"
		], [
			"_", ""
		], trim($str)));
		if(!isset($b[1])){
			$meta = 0;
		}elseif(is_numeric($b[1])){
			$meta = (int) $b[1];
		}else{
			throw new \InvalidArgumentException("Unable to parse \"" . $b[1] . "\" from \"" . $str . "\" as a valid meta value");
		}

		if(is_numeric($b[0])){
			$item = self::get((int) $b[0], $meta);
		}elseif(defined(ItemIds::class . "::" . strtoupper($b[0]))){
			$item = self::get(constant(ItemIds::class . "::" . strtoupper($b[0])), $meta);
		}else{
			throw new \InvalidArgumentException("Unable to resolve \"" . $str . "\" to a valid item");
		}

		return $item;
	}

	public static function air() : Item{
		return self::$air ?? (self::$air = self::get(ItemIds::AIR, 0, 0));
	}

	/**
	 * Returns whether the specified item ID is already registered in the item factory.
	 *
	 * @param int $id
	 * @param int $variant
	 *
	 * @return bool
	 */
	public static function isRegistered(int $id, int $variant = 0) : bool{
		if($id < 256){
			if($id < 0){
				$id = 255 - $id;
			}
			return BlockFactory::isRegistered($id);
		}

		return isset(self::$list[self::getListOffset($id, $variant)]);
	}

	private static function getListOffset(int $id, int $variant) : int{
		if($id < -0x8000 or $id > 0x7fff){
			throw new \InvalidArgumentException("ID must be in range " . -0x8000 . " - " . 0x7fff);
		}
		return (($id & 0xffff) << 16) | ($variant & 0xffff);
	}
}