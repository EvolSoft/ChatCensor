<?php

/*
 * ChatCensor (v1.4) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 03:44 PM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{
    

	//About Plugin Const
	const PRODUCER = "EvolSoft";
	const VERSION = "1.4";
	const MAIN_WEBSITE = "http://www.evolsoft.tk";
	//Other Const
	//Prefix
	const PREFIX = "&c[-&aChatCensor&c-] ";
	
	public $cfg;
	
	public $muted;
	
	public function translateColors($symbol, $message){
	
		$message = str_replace($symbol."0", TextFormat::BLACK, $message);
		$message = str_replace($symbol."1", TextFormat::DARK_BLUE, $message);
		$message = str_replace($symbol."2", TextFormat::DARK_GREEN, $message);
		$message = str_replace($symbol."3", TextFormat::DARK_AQUA, $message);
		$message = str_replace($symbol."4", TextFormat::DARK_RED, $message);
		$message = str_replace($symbol."5", TextFormat::DARK_PURPLE, $message);
		$message = str_replace($symbol."6", TextFormat::GOLD, $message);
		$message = str_replace($symbol."7", TextFormat::GRAY, $message);
		$message = str_replace($symbol."8", TextFormat::DARK_GRAY, $message);
		$message = str_replace($symbol."9", TextFormat::BLUE, $message);
		$message = str_replace($symbol."a", TextFormat::GREEN, $message);
		$message = str_replace($symbol."b", TextFormat::AQUA, $message);
		$message = str_replace($symbol."c", TextFormat::RED, $message);
		$message = str_replace($symbol."d", TextFormat::LIGHT_PURPLE, $message);
		$message = str_replace($symbol."e", TextFormat::YELLOW, $message);
		$message = str_replace($symbol."f", TextFormat::WHITE, $message);
	
		$message = str_replace($symbol."k", TextFormat::OBFUSCATED, $message);
		$message = str_replace($symbol."l", TextFormat::BOLD, $message);
		$message = str_replace($symbol."m", TextFormat::STRIKETHROUGH, $message);
		$message = str_replace($symbol."n", TextFormat::UNDERLINE, $message);
		$message = str_replace($symbol."o", TextFormat::ITALIC, $message);
		$message = str_replace($symbol."r", TextFormat::RESET, $message);
	
		return $message;
	}
	
	public function onEnable(){
	    @mkdir($this->getDataFolder());
	    @mkdir($this->getDataFolder() . "denied-words/");
		$this->saveDefaultConfig();
		$this->cfg = $this->getConfig()->getAll();
		$this->getCommand("chatcensor")->setExecutor(new Commands\Commands($this));
		$this->getCommand("addword")->setExecutor(new Commands\AddWord($this));
		$this->getCommand("removeword")->setExecutor(new Commands\RemoveWord($this));
		$this->getCommand("mute")->setExecutor(new Commands\Mute($this));
		$this->getCommand("unmute")->setExecutor(new Commands\Unmute($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}
	
	public function getChars(){
		$config = $this->getConfig()->getAll();
		$chars = implode($config["char-check"]["allowed-chars"]);
		return $chars;
	}
	
	public function getWord($word){
		if(file_exists($this->getDataFolder() . "denied-words/" . strtolower($word . ".yml"))){
			$data = new Config($this->getDataFolder() . "denied-words/" . strtolower($word . ".yml"), Config::YAML);
			return $data->getAll();
		}else{
			return false; //Word not found
		}
	}
	
	public function wordExists($word){
		return file_exists($this->getDataFolder() . "denied-words/" . strtolower($word . ".yml"));
	}
	
	public function addWord($word){
		$word = strtolower($word);
		$default = array(
			"delete-message" => false,
			"enable-replace" => true,
			"replace-word" => "****",
			"sender" => array(
				"kick" => false,
			    "ban" => false,
		        ),
			"kick" => array(
				"message" => "Kicked for swearing!"
			),
			"ban" => array(
				"message" => "Banned for swearing!"
			)
		);
		$data = new Config($this->getDataFolder() . "denied-words/" . strtolower($word . ".yml"), Config::YAML);
		$data->setAll($default);
		$data->save();
	}
	
	public function removeWord($word){
		unlink($this->getDataFolder() . "denied-words/" . strtolower($word . ".yml"));
	}
	
	public function mutePlayer($player){
		$player = strtolower($player);
		if(isset($this->muted[$player])){
			//Player already muted
			return false;
		}else{
			$this->muted[$player] = "";
			//Player muted
			return true;
		}		
	}
	
	public function unmutePlayer($player){
		$player = strtolower($player);
		if(isset($this->muted[$player])){
			unset($this->muted[$player]);
			//Player unmuted
			return true;
		}else{
			//Player not muted
			return false;
		}
	}
	
	public function isMuted($player){
		$player = strtolower($player);
		return isset($this->muted[$player]);
	}
	
}
?>
