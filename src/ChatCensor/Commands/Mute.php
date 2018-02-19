<?php

/*
 * ChatCensor (v2.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 19/02/2018 12:44 AM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

use ChatCensor\ChatCensor;

class Mute extends PluginBase implements CommandExecutor {

    public function __construct(ChatCensor $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
		if($sender->hasPermission("chatcensor.commands.mute")){
			if(isset($args[0])){
				$args[0] = strtolower($args[0]);
				if($this->plugin->getServer()->getPlayer($args[0]) != null){
					$player = $args[0];
					if($this->plugin->isMuted($player)){
					    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cPlayer " . $player . " is already muted!"));
					    return true;
					}
					$time = $args;
					unset($time[0]);
					$time = implode($time);
					if($time == null){
					    $time = $this->plugin->cfg->getAll()["mute"]["time"];
					}
					$time = strtr($time, array("s" => "second", "m" => "minute", "h" => "hour", "d" => "day", "mth" => "month", "y" => "year"));
					$time = strtotime($time);
					if($time === false){
					    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cInvalid duration specified."));
					    return true;
					}else if($this->plugin->mutePlayer($player, $time)){
					    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&aYou muted &b" . $player . "&a for &b" . $this->plugin->formatInterval($time) . "&a."));
					   if($this->plugin->cfg->getAll()["mute"]["log-mute"]){
					       $this->plugin->getServer()->getPlayer($player)->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("muted"), array("PREFIX" => ChatCensor::PREFIX, "PLAYER" => $sender->getName(), "DURATION" => $this->plugin->formatInterval($time)))));
					   }
					}
				}else{
				    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cPlayer not found!"));
				}
			}else{
			    $sender->sendMessage($this->plugin->translateColors("&", ChatCensor::PREFIX . "&cUsage: /mute <player> [duration]"));
			}
		}else{
			$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
		}
		return true;
    }
}