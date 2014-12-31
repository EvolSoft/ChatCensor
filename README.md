# ChatCensor

Chat Censor plugin for PocketMine-MP

## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP Alpha_1.4 API 1.9.0

## Overview

**ChatCensor** allows you to block swam words, limit characters and mute players in chat

**EvolSoft Website:** http://www.evolsoft.tk

***To prevent bugs, delete all old plugin data if you are updating ChatCensor.***

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

With ChatCensor you can block swam words, you can limit characters in chat and you can mute players. (read documentation)<br>
You can also easily set actions for each word.

**Commands:**

***/chatcensor*** *- ChatCensor commands*<br>
***/addword*** *- Add a denied-word*<br>
***/removeword*** *- Remove a denied-word*<br>
***/mute*** *- Mute player*<br>
***/unmute*** *- Unmute player*

**To-Do:**

<dd>- Bug fix (if bugs will be found)</dd>
<dd>- Block spam</dd>
<dd>- Add ban time</dd>

## Documentation 

**Add and configure a denied word:**

1. Run the command "/addword <word>"<br>
2. Go to "ChatCensor/denied-words" directory and open the word file<br>
This is a word config file:
```yaml
---
#Delete the message
delete-message: false
#Enable word replace
enable-replace: true
#Word that will be replaced (only if enable-replace is set to true)
replace-word: "****"
#Sender settings
sender:
#Kick player (if ban is set to true, action will be cancelled)
kick: false
#Ban player (if kick is set to true, action will be cancelled)
ban: false
#Kick settings
kick:
#Kick message
message: "Kicked for swearing!"
#Ban settings (ban duration will be implemented in next version)
ban:
#Ban message
message: "Banned for swearing!"
...
```

**Configuration (config.yml):**

```yaml
---
#Censor settings (Censor blocks swear words from chat, it can be bypassed with the permission: chatcensor.bypass.censor)
censor:
#Enable Censor
enabled: true
#if you enable allow-bypassing, players can bypass Censor with the permission: chatcensor.bypass.censor
allow-bypassing: false
#Log "No swearing" message to player
log-to-player: true
#Mute
mute:
#Log message to player when it is muted
log-to-player: true
#Log message to player when it is unmuted
log-unmute: true
#Keep player muted when it relog in the server
keep-on-relogin: true
#CharCheck (CharCheck limits characters in chat)
char-check:
#Enable CharCheck
enabled: true
#if you enable allow-bypassing, players can bypass CharCheck with the permission: chatcensor.bypass.char-check
allow-bypassing: false
#Log message to player
log-to-player: true
#Allow players to use "\" in chat messages
allow-backslash: false
# List of allowed characters (Don't forget to add the character in quotes)
allowed-chars:
- "Q"
- "W"
- "E"
- "R"
- "T"
- "Y"
- "U"
- "I"
- "O"
- "P"
- "A"
- "S"
- "D"
- "F"
- "G"
- "H"
- "J"
- "K"
- "L"
- "Z"
- "X"
- "C"
- "V"
- "B"
- "N"
- "M"
- "q"
- "w"
- "e"
- "r"
- "t"
- "y"
- "u"
- "i"
- "o"
- "p"
- "a"
- "s"
- "d"
- "f"
- "g"
- "h"
- "j"
- "k"
- "l"
- "z"
- "x"
- "c"
- "v"
- "b"
- "n"
- "m"
- "1"
- "2"
- "3"
- "4"
- "5"
- "6"
- "7"
- "8"
- "9"
- "0"
- "."
- ":"
- ","
- ";"
- "-"
- "_"
- "|"
- "!"
- "/"
- "?"
...
```

**Commands:**

<dd><i><b>/chatcensor</b> - ChatCensor commands (aliases: [cc, chatc, censor])</i></dd>
<dd><i><b>/addword &lt;word&gt;</b> - Add a denied-word</i></dd>
<dd><i><b>/removeword &lt;word&gt;</b> - Remove a denied-word</i></dd>
<dd><i><b>/mute &lt;player&gt;</b> - Mute player</i></dd>
<dd><i><b>/unmute &lt;player&gt;</b> - Unmute player</i></dd>
<br>
**Permissions:**

- <dd><i><b>chatcensor.*</b> - ChatCensor permissions.</i></dd>
- <dd><i><b>chatcensor.bypass.*</b> - Bypass ChatCensor permissions.</i></dd>
- <dd><i><b>chatcensor.bypass.char-check</b> - Bypass ChatCensor CharCheck permissions.</i></dd>
- <dd><i><b>chatcensor.bypass.censor</b> - Bypass ChatCensor Censor permissions.</i></dd>
- <dd><i><b>chatcensor.commands.*</b> - ChatCensor commands permissions.</i></dd>
- <dd><i><b>chatcensor.commands.help</b> - ChatCensor command Help permission.</i></dd>
- <dd><i><b>chatcensor.commands.info</b> - ChatCensor command Info permission.</i></dd>
- <dd><i><b>chatcensor.commands.reload</b> - ChatCensor command Reload permission.</i></dd>
- <dd><i><b>chatcensor.commands.addword</b> - ChatCensor command AddWord permission.</i></dd>
- <dd><i><b>chatcensor.commands.removeword</b> - ChatCensor command RemoveWord permission.</i></dd>
- <dd><i><b>chatcensor.commands.mute</b> - ChatCensor command Mute permission.</i></dd>
- <dd><i><b>chatcensor.commands.unmute</b> - ChatCensor command Unmute permission.</i></dd>
