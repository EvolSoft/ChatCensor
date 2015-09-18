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

class RemoveWord extends PluginBase implements CommandExecutor{

	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    	$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    		case "removeword":
    			if($sender->hasPermission("chatcensor.commands.removeword")){
    				if(isset($args[0])){
    					$args[0] = strtolower($args[0]);
    					//Check if word exists
    					if($this->plugin->wordExists($args[0])){
    						$this->plugin->removeWord($args[0]);
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aWord removed!"));
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cWord not found."));
    					}
    				}else{
    					$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cUsage: /removeword <word>"));
    				}
    			}else{
    				$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    				break;
    			}
    	}
    }
}
