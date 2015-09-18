<?php

/*
 * ChatCensor (v1.4) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 03:44 PM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\permission\Permission;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use ChatCensor\Main;
use ChatCensor\EventListener;

class Mute extends PluginBase implements CommandExecutor{

	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    	$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    		case "mute":
    			if($sender->hasPermission("chatcensor.commands.mute")){
    				if(isset($args[0])){
    					$args[0] = strtolower($args[0]);
    					//Check if player exists
    					if($this->plugin->getServer()->getPlayer($args[0]) != null){
    						$player = $args[0];
    						//Check if player is already muted
    						if($this->plugin->mutePlayer($player)){
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aYou muted &b" . $player));
    						}else{
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cPlayer " . $player . " is already muted!"));
    						}
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cPlayer not found!"));
    					}
    				}else{
    					$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cUsage: /mute <player>"));
    				}
    			}else{
    				$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    				break;
    			}
    	}
    }
}
