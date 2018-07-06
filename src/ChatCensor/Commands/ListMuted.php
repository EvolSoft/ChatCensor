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

class ListMuted extends PluginCommand implements CommandExecutor {
    
    /** @var ChatCensor */
    private $plugin;
    
    public function __construct(ChatCensor $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
        if($sender->hasPermission("chatcensor.commands.listmuted")){
            $mlist = $this->plugin->muted->getAll();
            $sender->sendMessage(TextFormat::colorize("&bMuted players:"));
            foreach($mlist as $muted => $time){
                if($this->plugin->isMuted($muted)){
                    $sender->sendMessage(TextFormat::colorize("&a" . $muted . "&e (expires after " . $this->plugin->formatInterval($time) . ")"));
                }
            }
            if(($mlist = $this->plugin->muted->getAll()) == null){
                $sender->sendMessage(TextFormat::colorize("&aNo players are muted."));
            }
        }else{
            $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
        }
        return true;
    }
}