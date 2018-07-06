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

class Mute extends PluginCommand implements CommandExecutor {

    /** @var ChatCensor */
    private $plugin;
    
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
					    $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &cPlayer " . $player . " is already muted!"));
					    return true;
					}
					$time = $args;
					unset($time[0]);
					$time = implode($time);
					if($time == null){
					    $time = $this->plugin->cfg["mute"]["time"];
					}
					$time = strtr($time, array("s" => "second", "m" => "minute", "h" => "hour", "d" => "day", "mth" => "month", "y" => "year"));
					$time = strtotime($time);
					if($time === false){
					    $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &cInvalid duration specified."));
					    return true;
					}else if($this->plugin->mutePlayer($player, $time)){
					    $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &aYou muted &b" . $player . "&a for &b" . $this->plugin->formatInterval($time) . "&a."));
					   if($this->plugin->cfg["mute"]["log-mute"]){
					       $this->plugin->getServer()->getPlayer($player)->sendMessage(TextFormat::colorize($this->plugin->replaceVars($this->plugin->getMessage("muted"), array("PREFIX" => ChatCensor::PREFIX, "PLAYER" => $sender->getName(), "DURATION" => $this->plugin->formatInterval($time)))));
					   }
					}
				}else{
				    $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &cPlayer not found!"));
				}
			}else{
			    $sender->sendMessage(TextFormat::colorize(ChatCensor::PREFIX . " &cUsage: /mute <player> [duration]"));
			}
		}else{
		    $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
		}
		return true;
    }
}