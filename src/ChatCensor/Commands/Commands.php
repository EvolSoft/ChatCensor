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

class Commands extends PluginBase implements CommandExecutor{

	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    	$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    		case "chatcensor":
    			if(isset($args[0])){
    				$args[0] = strtolower($args[0]);
    				if($args[0]=="help"){
    					if($sender->hasPermission("chatcensor.commands.help")){
    						$sender->sendMessage($this->plugin->translateColors("&", "&c== &eAvailable Commands &c=="));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/cc info &c->&e Show info about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/cc help &c->&e Show help about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/cc reload &c->&e Reload the config"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/addword &c->&e Add a denied word"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/removeword &c->&e Remove a denied word"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/mute &c->&e Mute player"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/unmute &c->&e Unmute player"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="info"){
    					if($sender->hasPermission("chatcensor.commands.info")){
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&eChatCensor &av" . Main::VERSION . " &edeveloped by&a " . Main::PRODUCER));
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&eWebsite &a" . Main::MAIN_WEBSITE));
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="reload"){
    					if($sender->hasPermission("chatcensor.commands.reload")){
    						$this->plugin->reloadConfig();
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aConfiguration Reloaded."));
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}else{
    					if($sender->hasPermission("chatcensor")){
    						$sender->sendMessage($this->plugin->translateColors("&",  Main::PREFIX . "&cSubcommand &a" . $args[0] . " &cnot found. Use &a/cc help &cto show available commands"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}
    				}else{
    					if($sender->hasPermission("chatcensor.commands.help")){
    						$sender->sendMessage($this->plugin->translateColors("&", "&c== &eAvailable Commands &c=="));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/cc info &c->&e Show info about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/cc help &c->&e Show help about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/cc reload &c->&e Reload the config"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/addword &c->&e Add a denied word"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/removeword &c->&e Remove a denied word"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/mute &c->&e Mute player"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&a/unmute &c->&e Unmute player"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}
    			}
    	}
}
?>
