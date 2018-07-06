<?php

/*
 * ChatCensor v2.3 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

use ChatCensor\ChatCensor;

class RemoveWord extends PluginCommand implements CommandExecutor {
    
    /** @var ChatCensor */
    private $plugin;

    public function __construct(ChatCensor $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
		if($sender->hasPermission("chatcensor.commands.removeword")){
			if(isset($args[0])){
				$args[0] = strtolower($args[0]);
				if($this->plugin->wordExists($args[0])){
					$this->plugin->removeWord($args[0]);
					$sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &aWord removed!"));
				}else{
				    $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &cWord not found."));
				}
			}else{
			    $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &cUsage: /removeword <word>"));
			}
		}else{
		    $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
		}
		return true;
    }
}