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

namespace pocketmine\block;

use pocketmine\block\utils\BlockDataValidator;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Torch extends Flowable{

	protected $id = self::TORCH;

	/** @var int */
	protected $facing = Facing::UP;

	public function __construct(){

	}

	protected function writeStateToMeta() : int{
		return 6 - $this->facing;
	}

	public function readStateFromMeta(int $meta) : void{
		$this->facing = $meta === 5 ? Facing::UP : BlockDataValidator::readHorizontalFacing(6 - $meta);
	}

	public function getStateBitmask() : int{
		return 0b111;
	}

	public function getLightLevel() : int{
		return 14;
	}

	public function getName() : string{
		return "Torch";
	}

	public function onNearbyBlockChange() : void{
		$below = $this->getSide(Facing::DOWN);
		$face = Facing::opposite($this->facing);

		if($this->getSide($face)->isTransparent() and !($face === Facing::DOWN and ($below->getId() === self::FENCE or $below->getId() === self::COBBLESTONE_WALL))){
			$this->getLevel()->useBreakOn($this);
		}
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		if($blockClicked->canBeReplaced() and !$blockClicked->getSide(Facing::DOWN)->isTransparent()){
			$this->facing = Facing::UP;
			return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}elseif($face !== Facing::DOWN and (!$blockClicked->isTransparent() or ($face === Facing::UP and ($blockClicked->getId() === self::FENCE or $blockClicked->getId() === self::COBBLESTONE_WALL)))){
			$this->facing = $face;
			return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}else{
			static $faces = [
				Facing::SOUTH, Facing::WEST, Facing::NORTH, Facing::EAST, Facing::DOWN
			];
			foreach($faces as $side){
				$block = $this->getSide($side);
				if(!$block->isTransparent()){
					$this->facing = Facing::opposite($side);
					return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
				}
			}
		}
		return false;
	}
}
