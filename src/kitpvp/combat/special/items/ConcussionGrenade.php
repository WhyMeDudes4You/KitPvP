<?php namespace kitpvp\combat\special\items;

use kitpvp\combat\special\items\types\Throwable;

use pocketmine\Player;
use pocketmine\entity\{
	Effect,
	Entity,
	EffectInstance
};
use pocketmine\network\mcpe\protocol\LevelEventPacket;

use kitpvp\KitPvP;

class ConcussionGrenade extends Throwable{

	public function __construct($meta = 0, $count = 1){
		parent::__construct(384, $meta, "Concussion Grenade");
		$this->setCount($count);

		$this->init();
	}

	public function getDescription(){
		return "Blinds and slows down opponents within 5 blocks. Easy escape route.";
	}

	public function concuss(Entity $victim, Player $thrower){
		$combat = KitPvP::getInstance()->getCombat();
		$teams = $combat->getTeams();
		if($victim instanceof Player && ($teams->sameTeam($victim, $thrower) || KitPvP::getInstance()->getArena()->getSpectate()->isSpectating($victim))){
			return;
		}
		$combat->getSlay()->damageAs($thrower, $victim, 5);

		$pk = new LevelEventPacket();
		$pk->evid = 3501;
		$pk->position = $victim->asVector3();
		$pk->data = 0;
		foreach($victim->getViewers() as $p) $p->dataPacket($pk);

		$victim->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 20 * 8, 3));
		$victim->addEffect(new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 20 * 8));

		if($victim instanceof Player){
			$session = KitPvP::getInstance()->getKits()->getSession($victim);
			if($session->hasKit() && $session->getKit()->getName() == "scout"){
				$as = KitPvP::getInstance()->getAchievements()->getSession($thrower);
				if(!$as->hasAchievement("countered")){
					$as->get("countered");
				}
			}
		}
	}

}