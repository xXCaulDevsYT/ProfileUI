<?php

namespace Infernus101\window;

use Infernus101\Main;
use Infernus101\window\Window;
use Infernus101\window\Handler;
use pocketmine\utils\TextFormat;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Player;

class ProfileWindow extends Window {
	public function process(): void {

		$flag = true;
		$name = $this->args->getName();
		$manager = $this->pl->getServer()->getPluginManager();

		if($this->pl->config->get("rank") == 1){
			$pp = $manager->getPlugin("PurePerms");
			if(!is_null($func = $pp->getUserDataMgr()->getGroup($this->args))){
				$rank = $func->getName();
			}
		else{
			$rank = '-';
		}
		}

		if($this->pl->config->get("money") == 1){
			$eco = $manager->getPlugin("EconomyAPI");
			$money = $eco->myMoney($name);
			if($money == false){
				$money = '-';
			}
		}

		if($this->pl->config->get("faction") == 1){
			$f = $manager->getPlugin("FactionsPro");
			if($f->isInFaction($name)){
			$fac = $f->getPlayerFaction($name);
			}
		else{
			$fac = '-';	
		}
		}

		if($this->pl->config->get("last-seen") == 1){
			if($this->args instanceof Player){
				$status = 'Online';
				$flag = true;
			}
		else{
			$status = 'Offline';
			$date = date("l, F j, Y", ($last = $this->args->getLastPlayed() / 1000));
			$time = date("h:ia", $last);
			$flag = false;
		}
		}

		if($this->pl->config->get("first-played") == 1){
			$date2 = date("l, F j, Y", ($first = $this->args->getFirstPlayed() / 1000));
			$time2 = date("h:ia", $first);
		}
		
		if($this->pl->config->get("mining-record") == 1){
			$stat = $this->pl->getStat($this->args);
			$mined = $stat["mining"];
		}
		
		if($this->pl->config->get("pvp-record") == 1){
			$stat = $this->pl->getStat($this->args);
			$kills = $stat["kills"];
			$deaths = $stat["deaths"];
		}
		
		if($this->pl->config->get("kdr") == 1){
			if($kills > 0 and $deaths > 0){
			$kdr = round($kills/$deaths);
			}
			else{
			$kdr = 'N/A';
			}
		}

		$name2 = ucfirst($name);
		$this->data = [
			"type" => "custom_form",
			"title" => TextFormat::AQUA.TextFormat::DARK_AQUA."$name2"."'s §bProfile",
			"content" => []
		];

		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dName: ".TextFormat::DARK_PURPLE."$name2"];

		if($this->pl->config->get("rank") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dRank: ".TextFormat::DARK_PURPLE."$rank"];
		}

		if($this->pl->config->get("money") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dMoney: ".TextFormat::DARK_PURPLE."$money"];
		}

		if($this->pl->config->get("faction") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dFaction: ".TextFormat::DARK_PURPLE."$fac"];
		}
		
		if($this->pl->config->get("mining-record") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dBlocks broken: ".TextFormat::DARK_PURPLE."$mined"];
		}
		
		if($this->pl->config->get("pvp-record") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dKills: ".TextFormat::DARK_PURPLE."$kills"];
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dDeaths: ".TextFormat::WHITE."$deaths"];
		}
		
		if($this->pl->config->get("kdr") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dKills/Deaths: ".TextFormat::DARK_PURPLE."$kdr"];
		}

		if($this->pl->config->get("first-played") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dFirst Played: ".TextFormat::DARK_PURPLE."$date2 at $time2"];
		}

		if($this->pl->config->get("last-seen") == 1){
			if($flag == true){
			$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dStatus: ".TextFormat::DARK_PURPLE."$status"];
			}
			if($flag == false){
			$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dStatus: ".TextFormat::DARK_PURPLE."$status"];
			$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."§dLast seen: ".TextFormat::DARK_PURPLE."$date at $time"];	
			}
		}

	}

	public function handle(ModalFormResponsePacket $packet): bool {
		return true;
	}
}
