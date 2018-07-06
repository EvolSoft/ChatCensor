<?php

/*
 * ChatCensor v2.3 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class ChatCensor extends PluginBase {
    
    /** @var string */
	const PREFIX = "&c[-&aChatCensor&c-]";
	
	/** @var string */
	const API_VERSION = "2.0";
	
	/** @var array */
	public $cfg;
	
	/** @var Config */
	public $muted;
	
	/** @var Config */
	private $words;
	
	/** @var Config */
	private $messages;
	
	/** @var ChatCensor */
	private static $instance = null;
	
	public function onLoad(){
	    if(!(self::$instance instanceof ChatCensor)){
	        self::$instance = $this;
	    }
	}
	
	public function onEnable(){
	    @mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->saveResource("messages.yml");
		$this->words = new Config($this->getDataFolder() . "words.yml", Config::YAML);
		$this->muted = new Config($this->getDataFolder() . "muted.yml", Config::YAML);
		$this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
		$this->cfg = $this->getConfig()->getAll();
		$this->getCommand("chatcensor")->setExecutor(new Commands\Commands($this));
		$this->getCommand("addword")->setExecutor(new Commands\AddWord($this));
		$this->getCommand("removeword")->setExecutor(new Commands\RemoveWord($this));
		$this->getCommand("mute")->setExecutor(new Commands\Mute($this));
		$this->getCommand("unmute")->setExecutor(new Commands\Unmute($this));
		$this->getCommand("listmuted")->setExecutor(new Commands\ListMuted($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}
	
	/**
	 * Get ChatCensor API
	 *
	 * @return ChatCensor
	 */
	public static function getAPI(){
	    return self::$instance;
	}
	
	/**
	 * Get ChatCensor version
	 *
	 * @return string
	 */
	public function getVersion(){
	    return $this->getVersion();
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
	 * Reload ChatCensor configuration
	 */
	public function reload(){
	    $this->reloadConfig();
	    $this->cfg = $this->getConfig()->getAll();
	    $this->muted->reload();
	    $this->words->reload();
	    $this->messages->reload();
	}
	
	/**
	 * Get the list of allowed characters
	 * 
	 * @return string
	 */
	public function getAllowedChars(){
	    return $this->cfg["char-check"]["allowed-chars"];
	}
	
	/**
	 * Get the list of unallowed characters
	 * 
	 * @return string
	 */
	public function getUnallowedChars(){
	    return $this->cfg["char-check"]["unallowed-chars"];
	}
	
	/**
	 * Get a censored word
	 * 
	 * @param string $word
	 * 
	 * @return array|bool
	 */
	public function getWord($word){
	    if($this->cfg["censor"]["advanced-mode"]){
	        if($this->wordExists($word, $k)){
	            return $this->words->get($k);
	        }
	        return false;
	    }
	    if($this->wordExists($word)){
	        return $this->words->get($word);
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
	    if($this->cfg["censor"]["advanced-mode"]){
	        foreach($this->words->getAll() as $key => $value){
	            if(strpos($word, $key) !== false){
	                $k = $key;
	                return true;
	            }
	        }
	        return false;
	    }
	    $k = $word;
	    return $this->words->exists($word);
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