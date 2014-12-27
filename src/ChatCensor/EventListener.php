<?php

/*
 * ChatCensor (v1.4) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 03:44 PM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class EventListener extends PluginBase implements Listener{
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function onChatCommand(PlayerCommandPreprocessEvent $event){
		$message = $event->getMessage();
		$player = $event->getPlayer();
		$this->cfg = $this->plugin->getConfig()->getAll();
		//Check if message is not a command
		if($message{0} != "/"){
			//Check if player is muted
			if($this->plugin->isMuted(strtolower($player->getName()))){
				if($this->cfg["mute"]["log-to-player"]){
					$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't send messages because you were muted"));
				}
				$event->setCancelled(true);
			}else{
				//if char-check finds an illegal char, the censor will be bypassed
				$status = 0;
				//Check if CharCheck is enabled
				if($this->cfg["char-check"]["enabled"] == true){
					//Checking if bypass is allowed
					if($this->cfg["char-check"]["allow-bypassing"] == true){
						//Checking CharCheck bypass permission
						if($player->hasPermission("chatcensor.bypass.char-check") != true){
							//Char checker
							$getchar = $this->plugin->getChars();
							$getchar = $getchar . " ";
							if($this->cfg["char-check"]["allow-backslash"] == true){
								$getchar = $getchar . "\\";
							}
							$allowedchr = str_split($getchar);
							$messagearray = str_split($message);
							for($i = 0; $i < count($messagearray); $i++){
								if(in_array($messagearray[$i], $allowedchr)==false){
									if($this->cfg["char-check"]["log-to-player"] == true){
										$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't send this message: it contains invalid characters."));
									}
									$status = 1;
									$event->setCancelled(true);
								}
							}
						}
					}else{
						//No-bypassing
						//Char checker
						$getchar = $this->plugin->getChars();
						$getchar = $getchar . " ";
						if($this->cfg["char-check"]["allow-backslash"] == true){
							$getchar = $getchar . "\\";
						}
						$allowedchr = str_split($getchar);
						$messagearray = str_split($message);
						for($i = 0; $i < count($messagearray); $i++){
							if(in_array($messagearray[$i], $allowedchr)==false){
								if($this->cfg["char-check"]["log-to-player"] == true){
									$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't send this message: it contains invalid characters."));
								}
								$status = 1;
								$event->setCancelled(true);
							}
						}
					}
				}
				//Check if Censor is enabled
				if($this->cfg["censor"]["enabled"] == true && $status == 0){
					$iskicked = 0;
					$isbanned = 0;
					$result = 0;
					//Checking if bypass is allowed
					if($this->cfg["censor"]["allow-bypassing"] == true){
						//Checking CharCheck bypass permission
						if($player->hasPermission("chatcensor.bypass.censor") != true){
							$tempmessage = $message;
							$messagewords = str_word_count($message, 1);
							for($i = 0; $i < count($messagewords); $i++){
								if($this->plugin->wordExists($messagewords[$i])){
									//Check Word Config
									$tmp = $this->plugin->getWord($messagewords[$i]);
									if($tmp["delete-message"] == true){
										$event->setCancelled(true);
									}
									if($tmp["enable-replace"] == true){
										/*$length = strlen($messagewords[$i]);
										 $replace = "";
										for ($l = 0; $l < $length; $l++){
										$replace = $replace . "*";
										}
										$tempmessage = str_replace($messagewords[$i],$replace,$tempmessage);*/
										$replace = $tmp["replace-word"];
										$tempmessage = str_replace($messagewords[$i],$replace,$tempmessage);
									}
									if($this->cfg["censor"]["log-to-player"] == true){
										$result = 1;
									}
									if($tmp["sender"]["kick"] == true){
										if($iskicked == 0 && $isbanned == 0){
											$player->kick($tmp["kick"]["message"]);
											$iskicked = 1;
										}
									}else{
										if($tmp["sender"]["ban"] == true){
											if($iskicked == 0 && $isbanned == 0){
												$this->plugin->getServer()->getNameBans()->addBan($player->getName(), $tmp["ban"]["message"]);
												$player->kick($tmp["ban"]["message"]);
												$isbanned = 1;
											}
										}
									}
								}
							}
							$event->setMessage($tempmessage);
							if($result == 1){
								$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cNo Swearing!"));
							}
						}
					}else{
						//No-bypassing
						$tempmessage = $message;
						$messagewords = str_word_count($message, 1);
						for($i = 0; $i < count($messagewords); $i++){
							if($this->plugin->wordExists($messagewords[$i])){
								//Check Word Config
								$tmp = $this->plugin->getWord($messagewords[$i]);
								if($tmp["delete-message"] == true){
									$event->setCancelled(true);
								}
								if($tmp["enable-replace"] == true){
									/*$length = strlen($messagewords[$i]);
									 $replace = "";
									for ($l = 0; $l < $length; $l++){
									$replace = $replace . "*";
									}
									$tempmessage = str_replace($messagewords[$i],$replace,$tempmessage);*/
									$replace = $tmp["replace-word"];
									$tempmessage = str_replace($messagewords[$i],$replace,$tempmessage);
								}
								if($this->cfg["censor"]["log-to-player"] == true){
									$result = 1;
								}
								if($tmp["sender"]["kick"] == true){
									if($iskicked == 0 && $isbanned == 0){
										$player->kick($tmp["kick"]["message"]);
										$iskicked = 1;
									}
								}else{
									if($tmp["sender"]["ban"] == true){
										if($iskicked == 0 && $isbanned == 0){
											$this->plugin->getServer()->getNameBans()->addBan($player->getName(), $tmp["ban"]["message"]);
											$player->kick($tmp["ban"]["message"]);
											$isbanned = 1;
										}
									}
								}
							}
						}
						$event->setMessage($tempmessage);
						if($result == 1){
							$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cNo Swearing!"));
						}
					}
				}	
			}
		}
	}
	
	public function onQuit(PlayerQuitEvent $event){
		$this->cfg = $this->plugin->getConfig()->getAll();
		$player = $event->getPlayer();
		if($this->cfg["mute"]["keep-on-relogin"] != true){
			$this->plugin->unmutePlayer($player->getName());
		}
	}
	
}
?>
