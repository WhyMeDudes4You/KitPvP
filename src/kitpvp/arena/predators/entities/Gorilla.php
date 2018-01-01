<?php namespace kitpvp\arena\predators\entities;

use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Skin;

class Gorilla extends Boss{

	public $attackDamage = 7;
	public $speed = 0.55;

	public function __construct(Level $level, CompoundTag $nbt){
		parent::__construct($level, $nbt);
		$this->setSkin(new Skin("Standard_Custom", file_get_contents("/home/data/skins/gorillaboss.dat")));
	}

	public function getType(){
		return "Gorilla";
	}

}