<?php

/*
 * ChatCensor (v2.0) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 08/01/2018 04:05 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use const pocketmine\API_VERSION;

class ChatCensor extends PluginBase {
    
    /** @var string */
	const PREFIX = "&c[-&aChatCensor&c-] ";
	
	/** @var Config */
	public $cfg;
	
	/** @var Config */
	public $muted;
	
	/** @var Config */
	private $words;
	
	/** @var Config */
	private $messages;
	
	/** @var float */
	const API_VERSION = 1.0;
	
	/** @var ChatCensor $object */
	private static $object = null;
	
	/**
	 * Get ChatCensor API
	 *
	 * @return ChatCensor
	 */
	public static function getAPI() : ChatCensor {
	    return self::$object;
	}
	
	/**
	 * Translate Minecraft colors
	 * 
	 * @param string $symbol
	 * @param string $message
	 * 
	 * @return string
	 */
	public function translateColors($symbol, $message){
	    $message = str_replace($symbol . "0", TextFormat::BLACK, $message);
	    $message = str_replace($symbol . "1", TextFormat::DARK_BLUE, $message);
	    $message = str_replace($symbol . "2", TextFormat::DARK_GREEN, $message);
	    $message = str_replace($symbol . "3", TextFormat::DARK_AQUA, $message);
	    $message = str_replace($symbol . "4", TextFormat::DARK_RED, $message);
	    $message = str_replace($symbol . "5", TextFormat::DARK_PURPLE, $message);
	    $message = str_replace($symbol . "6", TextFormat::GOLD, $message);
	    $message = str_replace($symbol . "7", TextFormat::GRAY, $message);
	    $message = str_replace($symbol . "8", TextFormat::DARK_GRAY, $message);
	    $message = str_replace($symbol . "9", TextFormat::BLUE, $message);
	    $message = str_replace($symbol . "a", TextFormat::GREEN, $message);
	    $message = str_replace($symbol . "b", TextFormat::AQUA, $message);
	    $message = str_replace($symbol . "c", TextFormat::RED, $message);
	    $message = str_replace($symbol . "d", TextFormat::LIGHT_PURPLE, $message);
	    $message = str_replace($symbol . "e", TextFormat::YELLOW, $message);
	    $message = str_replace($symbol . "f", TextFormat::WHITE, $message);
	    
	    $message = str_replace($symbol . "k", TextFormat::OBFUSCATED, $message);
	    $message = str_replace($symbol . "l", TextFormat::BOLD, $message);
	    $message = str_replace($symbol . "m", TextFormat::STRIKETHROUGH, $message);
	    $message = str_replace($symbol . "n", TextFormat::UNDERLINE, $message);
	    $message = str_replace($symbol . "o", TextFormat::ITALIC, $message);
	    $message = str_replace($symbol . "r", TextFormat::RESET, $message);
	    return $message;
	}
	
	public function onLoad(){
	    if(!(self::$object instanceof ChatCensor)){
	        self::$object = $this;
	    }
	}
	
	public function onEnable(){
	    @mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->saveResource("messages.yml");
		$this->words = new Config($this->getDataFolder() . "words.yml", Config::YAML);
		$this->muted = new Config($this->getDataFolder() . "muted.yml", Config::YAML);
		$this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
		$this->cfg = $this->getConfig();
		$this->getCommand("chatcensor")->setExecutor(new Commands\Commands($this));
		$this->getCommand("addword")->setExecutor(new Commands\AddWord($this));
		$this->getCommand("removeword")->setExecutor(new Commands\RemoveWord($this));
		$this->getCommand("mute")->setExecutor(new Commands\Mute($this));
		$this->getCommand("unmute")->setExecutor(new Commands\Unmute($this));
		$this->getCommand("listmuted")->setExecutor(new Commands\ListMuted($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}
	
	/**
	 * Get ChatCensor API version
	 *
	 * @return string
	 */
	public function getAPIVersion(){
	    return self::API_VERSION;
	}
	
	/**
	 * Get the list of allowed characters
	 * 
	 * @return string
	 */
	public function getAllowedChars(){
	    return $this->getConfig()->getAll()["char-check"]["allowed-chars"];
	}
	
	/**
	 * Get the list of unallowed characters
	 * 
	 * @return string
	 */
	public function getUnallowedChars(){
	    return $this->getConfig()->getAll()["char-check"]["unallowed-chars"];
	}
	
	/**
	 * Get a censored word
	 * 
	 * @param string $word
	 * 
	 * @return array|bool
	 */
	public function getWord($word){
	    if($this->cfg->getAll()["censor"]["advanced-mode"]){
	        if($this->wordExists($word, $k)){
	            return $this->words->getAll()[$k];
	        }
	        return false;
	    }
	    if($this->wordExists($word)){
	        return $this->words->getAll()[$word];
	    }
	    return false;
	}
	
	/**
	 * Check if the specified word is censored
	 * 
	 * @param string $word
	 * @param string $k
	 * 
	 * @return bool
	 */
	public function wordExists($word, &$k = null) : bool {
	    if($this->cfg->getAll()["censor"]["advanced-mode"]){
	        foreach($this->words->getAll() as $key => $value){
	            if(strpos($word, $key) !== false){
	                $k = $key;
	                return true;
	            }
	        }
	        return false;
	    }
	    $k = $word;
	    return isset($this->words->getAll()[$word]);
	}
	
	/**
	 * Add a censored word
	 * 
	 * @param string $word
	 */
	public function addWord($word){
		$word = strtolower($word);
		$hid = "";
		for($i = 0; $i < strlen($word); $i++){
		    $hid[$i] = "*";
		}
		$default = array(
			"delete-message" => false,
			"enable-replace" => true,
			"replace-word" => $hid,
			"commands" => array()
		);
		$this->words->set($word, $default);
		$this->words->save();
	}
	
	/**
	 * Remove a censored word
	 * 
	 * @param string $word
	 * 
	 * @return bool
	 */
	public function removeWord($word) : bool {
	    if($this->wordExists($word)){
	        $this->words->remove($word);
	        $this->words->save();
	        return true;
	    }
	    return false;
	}
	
	/**
	 * Mute a player
	 * 
	 * @param string $player
	 * @param int $time
	 * 
	 * @return bool
	 */
	public function mutePlayer($player, $time) : bool {
		$player = strtolower($player);
		if($this->isMuted($player)){
		    return true;
		}
		$this->muted->set($player, $time);
		$this->muted->save();
		return true;		
	}
	
	/**
	 * Unmute a player
	 * 
	 * @param string $player
	 * 
	 * @return bool
	 */
	public function unmutePlayer($player) : bool {
		$player = strtolower($player);
		if($this->isMuted($player)){
			$this->muted->remove($player);
			$this->muted->save();
			return true;
		}
		return false;
	}
	
	/**
	 * Check if a player is muted
	 * 
	 * @param string $player
	 * 
	 * @return bool
	 */
	public function isMuted($player) : bool {
		$player = strtolower($player);
		if($this->muted->exists($player)){
		    if(time() > $this->muted->get($player)){
		        $this->muted->remove($player);
		        $this->muted->save();
		        return false;
		    }
		    return true;
		}
		return false;
	}
	
	/**
	 * Get ChatCensor message
     * 
     * @param string $id
     * 
     * @return string|bool
     */
	public function getMessage($id){
	    if($this->messages->exists($id)){
	        return $this->messages->get($id);
	    }
	}
	
	/**
	 * Reload ChatCensor messages
	 */
	public function reloadMessages(){
	    $this->messages->reload();
	}
	
	/**
	 * Reload words configuration
	 */
	public function reloadWords(){
	    $this->words->reload();
	}
	
	/**
	 * Replace variables inside a string
	 *
	 * @param string $str
	 * @param array $vars
	 *
	 * @return string
	 */
	public function replaceVars($str, array $vars){
	    foreach($vars as $key => $value){
	        $str = str_replace("{" . $key . "}", $value, $str);
	    }
	    return $str;
	}
	
	/**
	 * Convert a string to time
	 * 
	 * @param string $time
	 * 
	 * @return string
	 */
	public function formatInterval($time){
	    $now = new \DateTime("now");
	    $newtime = \DateTime::createFromFormat("H:i:s d/m/y", date("H:i:s d/m/y", $time));
	    $interval = $now->diff($newtime);
	    $str = null;
	    if($interval->y){
	        $str .= $interval->y . "y";
	    }
	    if($interval->m){
	        $str.= " " . $interval->m . "m";
	    }
	    if($interval->d){
	        $str.= " " . $interval->d . "d";
	    }
	    if($interval->h){
	        $str.= " " . $interval->h . "h";
	    }
	    if($interval->i){
	        $str.= " " . $interval->i . "min";
	    }
	    if($interval->s){
	        $str.= " " . $interval->s . "sec";
	    }
	    return trim($str);
	}
}