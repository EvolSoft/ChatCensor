<?php

/*
 * ChatCensor (v2.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 08/01/2018 01:37 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatCensor/blob/master/LICENSE)
 */

namespace ChatCensor;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class EventListener extends PluginBase implements Listener {
    
    private $lastmessage;
	
	public function __construct(ChatCensor $plugin){
		$this->plugin = $plugin;
	}
	
	/**
	 * @param PlayerCommandPreprocessEvent $event
	 */
	public function onChat(PlayerCommandPreprocessEvent $event){
		$message = $event->getMessage();
		$player = $event->getPlayer();
		$cfg = $this->plugin->getConfig()->getAll();
		//Check if this is a command and skip if check-commands is disabled
		if($message[0] == "/" && !$cfg["censor"]["check-commands"]){
		    return;
		}
		//Check if player is muted
		if($this->plugin->isMuted(strtolower($player->getName())) && $message[0] != "/"){
			if($cfg["mute"]["log-to-player"]){
			    $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("muted-error"), array("PREFIX" => ChatCensor::PREFIX))));
			}
			$event->setCancelled(true);
			return;
		}
		//Check if Anti-Caps is enabled
		if($cfg["anti-caps"]["enabled"]){
		    if($cfg["anti-caps"]["allow-bypassing"] && $player->hasPermission("chatcensor.bypass.anti-caps")){
		        goto spamcheck;
		    }
		    if(preg_match('/[A-Z]/', $message)){
		        if($cfg["anti-caps"]["block-message"]){
		            if($cfg["anti-caps"]["log-to-player"]){
		                $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("no-caps"), array("PREFIX" => ChatCensor::PREFIX))));
		            }
		            $event->setCancelled(true);
		            return;
		        }
		        $message = strtolower($message);
		    }  
		}
		spamcheck:
    		//Check if Anti-Spam is enabled
    		if($cfg["anti-spam"]["enabled"]){
    		    if($cfg["anti-spam"]["allow-bypassing"] && $player->hasPermission("chatcensor.bypass.anti-spam")){
    		        goto charcheck;
    		    }
    		    if(isset($this->lastmessage[$player->getName()])){
    		        if($cfg["anti-spam"]["mode"] == 0 || $cfg["anti-spam"]["mode"] == 2){
	                    if(strcasecmp($message, $this->lastmessage[$player->getName()]["message"]) == 0){
	                        if($cfg["anti-spam"]["log-to-player"]){
	                            $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("no-spam"), array("PREFIX" => ChatCensor::PREFIX))));
	                        }
	                        $event->setCancelled(true);
	                        return;
	                    }
    		        }
    		        if($cfg["anti-spam"]["mode"] == 1 || $cfg["anti-spam"]["mode"] == 2){
		                if(time() - $this->lastmessage[$player->getName()]["time"] <= $cfg["anti-spam"]["delay"]){
		                    if($cfg["anti-spam"]["log-to-player"]){
		                        $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("spam-delay"), array("PREFIX" => ChatCensor::PREFIX, "DELAY" => time() - $this->lastmessage[$player->getName()]["time"]))));
		                    }
		                    $event->setCancelled(true);
		                    return;
		                }
    		        }
    		    }
    		}
		charcheck:
    		//Check if CharCheck is enabled
    		if($cfg["char-check"]["enabled"]){
    			//Checking if bypass is allowed
    		    if($cfg["char-check"]["allow-bypassing"] && $player->hasPermission("chatcensor.bypass.char-check")){
    				goto censor;
    			}
    			//Check message length
    			if($cfg["char-check"]["max-length"] > 0 && strlen($message) > $cfg["char-check"]["max-length"]){
    			    if($cfg["char-check"]["log-to-player"]){
    			        $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("too-long"), array("PREFIX" => ChatCensor::PREFIX))));
    			    }
    			    $event->setCancelled(true);
    			    return;
    			}
    			//Check backslash
    			if(!$cfg["char-check"]["allow-backslash"]){
    			    if((bool) strpbrk($message, "\\")){
    			        if($cfg["char-check"]["log-to-player"]){
    			            $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("invalid"), array("PREFIX" => ChatCensor::PREFIX))));
    			        }
    			        $event->setCancelled(true);
    			        return;
    			    }
    			}
    			//Unallowed characters checker
    			$unallowed = $this->plugin->getUnallowedChars();
    			if($unallowed != ""){
    			    if((bool) strpbrk($message, $unallowed)){
    			        if($cfg["char-check"]["log-to-player"]){
    			            $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("invalid"), array("PREFIX" => ChatCensor::PREFIX))));
    			        }
    			        $event->setCancelled(true);
    			        return;
    			    }
    			}
    			//Allowed characters checker
    			$allowed = $this->plugin->getAllowedChars();
    			if($allowed != null){
    			    $allowed .= " ";
    			    $allowedchr = str_split($allowed);
    			    $messagearray = str_split($message);
    			    foreach($messagearray as $word){
    			        if(!in_array($word, $allowedchr)){
    			            if($cfg["char-check"]["log-to-player"]){
    			                $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("invalid"), array("PREFIX" => ChatCensor::PREFIX))));
    			            }
    			            $event->setCancelled(true);
    			            return;
    			        }
    			    }
    			}
    		}
		censor:
			//Check if Censor is enabled
			if($cfg["censor"]["enabled"]){
				//Checking if bypass is allowed
				if($cfg["censor"]["allow-bypassing"] && $player->hasPermission("chatcensor.bypass.censor")){
				    goto next;
				}
				$tempmessage = $message;
				$words = explode(" ", $message);
				//Check if websites are blocked
				if($cfg["censor"]["block-urls"] && preg_match("/\w+(\s+)?\.(\s+)?\w+(\s+)?\.(\s+)?\w+/i", $message)){
				    if($cfg["censor"]["log-to-player"]){
				        $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("no-urls"), array("PREFIX" => ChatCensor::PREFIX))));
				    }
				    $event->setCancelled(true);
				    return;
				}
				foreach($words as $word){
				    $key = null;
				    //Check if IP addresses are blocked
				    if($cfg["censor"]["block-ips"] && (filter_var($word, FILTER_VALIDATE_IP) != false)){
				        if($cfg["censor"]["log-to-player"]){
				            $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("no-ips"), array("PREFIX" => ChatCensor::PREFIX))));
				        }
				        $event->setCancelled(true);
				        return;
				    }
				    if($this->plugin->wordExists($word, $key)){
						//Check Word Config
				        $tmp = $this->plugin->getWord($word);
						if($tmp["delete-message"]){
							$event->setCancelled(true);
						}
						if($tmp["enable-replace"]){
							$replace = $tmp["replace-word"];
							$tempmessage = str_replace($key, $replace, $tempmessage);
						}
						if($cfg["censor"]["log-to-player"]){
						    $player->sendMessage($this->plugin->translateColors("&", $this->plugin->replaceVars($this->plugin->getMessage("no-swearing"), array("PREFIX" => ChatCensor::PREFIX))));
						}
					    foreach($tmp["commands"] as $cmd){
					        $cmd = $this->plugin->replaceVars($cmd, array("PLAYER" => $player->getName()));
					        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), $cmd);
					    }
					}
		      }
		      next:
		          $event->setMessage($tempmessage);
		}
		$this->lastmessage[$player->getName()]["message"] = $message;
		$this->lastmessage[$player->getName()]["time"] = time();
	}
}