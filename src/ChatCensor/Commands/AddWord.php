<?php

/*
 * ChatCensor (v2.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 08/01/2018 01:37 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

use ChatCensor\ChatCensor;

class AddWord extends PluginBase implements CommandExecutor {

	public function __construct(ChatCensor $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
		if($sender->hasPermission("chatcensor.commands.addword")){
			if(isset($args[0])){
				$args[0] = strtolower($args[0]);
				//Check if word exists
				if($this->plugin->wordExists($args[0])){
				    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cWord already added."));
				}else{
					$this->plugin->addWord($args[0]);
					$sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&aWord added!"));
				}
			}else{
			    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cUsage: /addword <word>"));
			}
		}else{
			$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
		}
		return true;
    }
}