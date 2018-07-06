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

class Commands extends PluginCommand implements CommandExecutor {
    
    /** @var ChatCensor */
    private $plugin;

    public function __construct(ChatCensor $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
        if(isset($args[0])){
    		$args[0] = strtolower($args[0]);
    		switch($args[0]){
    		    case "help":
			        goto help;
			    case "info":
			        if($sender->hasPermission("chatcensor.commands.info")){
			            $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &eChatCensor &av" . $this->plugin->getDescription()->getVersion() . "&e developed by &aEvolSoft"));
			            $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &eWebsite &a" . $this->plugin->getDescription()->getWebsite()));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			    case "reload":
			        if($sender->hasPermission("chatcensor.commands.reload")){
			            $this->plugin->reload();
			            $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &aConfiguration Reloaded."));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			    default:
			        if($sender->hasPermission("chatcensor")){
			            $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &cSubcommand &a" . $args[0] . " &cnot found. Use &a/cc help &cto show available commands."));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break; 
			}
			return true;
		}else{
		    help:
    		if($sender->hasPermission("chatcensor.commands.help")){
    		    $sender->sendMessage(TextFormat::colorize(" &c== &eAvailable Commands &c=="));
    		    $sender->sendMessage(TextFormat::colorize(" &a/cc info &c->&e Show info about this plugin"));
    		    $sender->sendMessage(TextFormat::colorize(" &a/cc help &c->&e Show help about this plugin"));
    		    $sender->sendMessage(TextFormat::colorize(" &a/cc reload &c->&e Reload the config"));
    		    $sender->sendMessage(TextFormat::colorize(" &a/addword &c->&e Add a censored word"));
    		    $sender->sendMessage(TextFormat::colorize(" &a/removeword &c->&e Remove a censored word"));
    		    $sender->sendMessage(TextFormat::colorize(" &a/mute &c->&e Mute a player"));
    		    $sender->sendMessage(TextFormat::colorize(" &a/unmute &c->&e Unmute a player"));
    		    $sender->sendMessage(TextFormat::colorize(" &a/listmuted &c->&e Get the list of muted players"));
    			return true;
    		}
    		$sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
    		return true;
    	}
    }
}