<?php

/*
 * ChatCensor (v2.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 08/01/2018 01:38 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

use ChatCensor\ChatCensor;

class Unmute extends PluginBase implements CommandExecutor {

    public function __construct(ChatCensor $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
    	if($sender->hasPermission("chatcensor.commands.unmute")){
    		if(isset($args[0])){
    			$args[0] = strtolower($args[0]);
    			//Check if player exists
    			if($this->plugin->getServer()->getPlayer($args[0]) != null){
    				$player = $args[0];
    				//Check if player is already muted
    				if($this->plugin->isMuted($player)){
    				    $this->plugin->unmutePlayer($player);
    				    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&aYou unmuted &b" . $player));
    					//Check if log unmute is enabled
    					if($this->plugin->getConfig()->getAll()["mute"]["log-unmute"]){
    					    $this->plugin->getServer()->getPlayer($player)->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("unmuted"), array("PREFIX" => ChatCensor::PREFIX, "PLAYER" => $sender->getName()))));
    					}
    				}else{
    				    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cPlayer " . $player . " is not muted!"));
    				}
    			}else{
    			    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cPlayer not found!"));
    			}
    		}else{
    		    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cUsage: /unmute <player>"));
    		}
    	}else{
    		$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    	}
    	return true;
    }
}