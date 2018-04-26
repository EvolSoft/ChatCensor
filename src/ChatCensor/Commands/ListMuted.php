<?php

/*
 * ChatCensor (v2.2) by EvolSoft
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

class ListMuted extends PluginBase implements CommandExecutor {
    
    public function __construct(ChatCensor $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
        if($sender->hasPermission("chatcensor.commands.listmuted")){
            $mlist = $this->plugin->muted->getAll();
            $sender->sendMessage($this->plugin->translateColors("&", "&bMuted players:"));
            foreach($mlist as $muted => $time){
                if($this->plugin->isMuted($muted)){
                    $sender->sendMessage($this->plugin->translateColors("&", "&a" . $muted . "&e (expires after " . $this->plugin->formatInterval($time) . ")"));
                }
            }
            if(($mlist = $this->plugin->muted->getAll()) == null){
                $sender->sendMessage($this->plugin->translateColors("&", "&aNo players are muted."));
            }
        }else{
            $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
        }
        return true;
    }
}