<?php

/*
 * ChatCensor (v2.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 08/01/2018 01:39 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

use ChatCensor\ChatCensor;

class Commands extends PluginBase implements CommandExecutor {

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
			            $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&eChatCensor &av" . $this->plugin->getDescription()->getVersion() . "&e developed by &aEvolSoft"));
			            $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&eWebsite &a" . $this->plugin->getDescription()->getWebsite()));
			            break;
			        }
			        $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
			        break;
			    case "reload":
			        if($sender->hasPermission("chatcensor.commands.reload")){
			            $this->plugin->reloadConfig();
			            $this->plugin->muted->reload();
			            $this->plugin->reloadWords();
			            $this->plugin->reloadMessages();
			            $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&aConfiguration Reloaded."));
			            break;
			        }
			        $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
			        break;
			    default:
			        if($sender->hasPermission("chatcensor")){
			            $sender->sendMessage($this->plugin->translateColors("&",  ChatCensor::PREFIX . "&cSubcommand &a" . $args[0] . " &cnot found. Use &a/cc help &cto show available commands."));
			            break;
			        }
			        $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
			        break; 
			}
			return true;
		}else{
		    help:
    		if($sender->hasPermission("chatcensor.commands.help")){
    			$sender->sendMessage($this->plugin->translateColors("&", "&c== &eAvailable Commands &c=="));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/cc info &c->&e Show info about this plugin"));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/cc help &c->&e Show help about this plugin"));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/cc reload &c->&e Reload the config"));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/addword &c->&e Add a censored word"));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/removeword &c->&e Remove a censored word"));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/mute &c->&e Mute a player"));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/unmute &c->&e Unmute a player"));
    			$sender->sendMessage($this->plugin->translateColors("&", "&a/listmuted &c->&e Get the list of muted players"));
    			return true;
    		}
    		$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    		return true;
    	}
    }
}