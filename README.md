![start2](https://cloud.githubusercontent.com/assets/10303538/6315586/9463fa5c-ba06-11e4-8f30-ce7d8219c27d.png)

# ChatCensor

A powerful chat censoring plugin for PocketMine-MP

## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP 1.7dev API 3.0.0-ALPHA7, 3.0.0-ALPHA8, 3.0.0-ALPHA9, 3.0.0-ALPHA10

## Overview

**ChatCensor** let you block censored words, IP addresses, websites, spam, caps, set allowed and unallowed characters and mute/unmute players in chat.

**EvolSoft Website:** https://www.evolsoft.tk

***To prevent bugs, delete all old plugin data if you are updating ChatCensor.***

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

With ChatCensor you can block censored words, IP addresses, websites, spam, caps, set allowed and unallowed characters and mute/unmute players in chat. (read documentation)<br>
You can also easily customize actions for each censored word.

***Features:***

- *Word censor:* block censored words on chat or even on commands!
- *URL/IP censor:* prevent players from sending IP addresses or URLs on chat
- *Anti-caps:* remove caps from messages or block them
- *Anti-spam:* advanced anti-spam check which will prevent players from sending the same message twice or spamming on chat
- *Mute/Unmute players:* temporarily or permanently mute players on chat
- *Maximum message length:* prevent players from sending long messages
- *Allowed/Unallowed characters:* limit allowed characters on messages
- Customizable user-side messages

**Commands:**

***/chatcensor*** *- ChatCensor commands*<br>
***/addword*** *- Add a denied-word*<br>
***/removeword*** *- Remove a denied-word*<br>
***/mute*** *- Mute player*<br>
***/unmute*** *- Unmute player*<br>
***/listmuted*** *- Get the list of muted players*

## Donate

Support the development of this plugin with a small donation by clicking [:dollar: here](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=flavius.c.1999@gmail.com&lc=US&item_name=www.evolsoft.tk&no_note=0&cn=&curency_code=EUR&bn=PP-DonationsBF:btn_donateCC_LG.gif:NonHosted). Thank you :smile:

## Documentation

**Configuration (config.yml):**

```yaml
---
# Censor settings
censor:
 # Enable censor
 enabled: true
 # Let players with chatcensor.bypass.censor permission bypass this restriction
 allow-bypassing: false
 # Check bad words inside words (may slightly slow down your server)
 advanced-mode: false
 # Check commands
 check-commands: false
 # Log message to player
 log-to-player: true
 # Block messages containing URLs (a message will be sent to the player if log-to-player is enabled)
 block-urls: true
 # Block messages containing IP addresses (a message will be sent to the player if log-to-player is enabled)
 block-ips: true
# Anti-caps settings
anti-caps:
 # Enable anti-caps
 enabled: true
 # Let players with chatcensor.bypass.anti-caps permission bypass this restriction
 allow-bypassing: false
 # Log message to player
 log-to-player: true
 # Block the message (if block-message is disabled, the message will be converted to lower case)
 block-message: true
# Anti-spam settings
anti-spam:
 # Enable anti-spam
 enabled: true
 # Let players with chatcensor.bypass.anti-spam permission bypass this restriction
 allow-bypassing: false
 # Log message to player
 log-to-player: true
 # Spam checking mode (0 = the same message can't be repeated, 1 = delayed spam checking, 2 = both)
 mode: 1
 # Delay (in seconds)
 delay: 5
# Mute/Unmute settings
mute:
 # Log the mute message to the player
 log-mute: true
 # Log the unmute message to the player
 log-unmute: true
 # Log message to player
 log-to-player: true
 # Default mute time
 time: "30m"
# Characters/Message checking settings
char-check:
 # Enable characters/message checking
 enabled: true
 # Let players with chatcensor.bypass.char-check permission bypass this restriction
 allow-bypassing: false
 # Log message to player
 log-to-player: true
 # Maximum message length (set to 0 to disable this feature)
 max-length: 0
 # Allow backslash in messages
 allow-backslash: false
 # Allowed characters in messages (set to "" to disable this feature)
 allowed-chars: "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890.:,;-_|!/?"
 # Unallowed characters in messages (set to "" to disable this feature)
 unallowed-chars: ""
...
```

**Messages (messages.yml):**

```yaml
---
# No URLs message
no-urls: "{PREFIX} &cYou can't send URLs on messages!"
# No IP addresses message
no-ips: "{PREFIX} &cYou can't send IP addresses on messages!"
# No swearing message
no-swearing: "{PREFIX} &cPlease don't swear!"
# No caps message
no-caps: "{PREFIX} &cUppercase characters are not allowed on messages"
# Invalid/Unallowed characters message
invalid: "{PREFIX} &cYou can't send this message because it contains invalid characters"
# Message too long message
too-long: "{PREFIX} &cYou can't send this message because it's too long"
# No spam message
no-spam: "{PREFIX} &cPlease don't spam"
# Spam delay message
spam-delay: "{PREFIX} &cPlease wait &e{DELAY} seconds&c before sending another message"
# Muted message (when the muted player tries to send a message)
muted-error: "{PREFIX} &cYou can't send message because you are muted"
# Muted message
muted: "{PREFIX} &cYou have been muted for &e{DURATION}&c by &e{PLAYER}&c!"
# Unmuted message
unmuted: "{PREFIX} &cYou have been unmuted by &e{PLAYER}&c!"
...
```

**Add and configure a denied word:**

1. Run the command "/addword &lt;word&gt;"<br>
2. Go to ChatCensor plugin directory and open "words.yml" file<br>
3. Find the world in the list and customize its settings

These are the censored word settings:

```yaml
---
# Delete the message containing the word
delete-message: false
# Replace the censored word
enable-replace: true
# The text which will replace the censored word
replace-word: "****"
# List of commands to execute
# Available tags:
#  - {PLAYER}: Player name of the player who sent the message containing the censored word
# Example:
#  commands:
#  - "tell {PLAYER} Do not swear please!"
#  - "ban {PLAYER}"
commands: []
...
```

**Commands:**

<dd><i><b>/chatcensor</b> - ChatCensor commands (aliases: [cc, chatc, censor])</i></dd>
<dd><i><b>/addword &lt;word&gt;</b> - Add a censored word</i></dd>
<dd><i><b>/removeword &lt;word&gt;</b> - Remove a censored word</i></dd>
<dd><i><b>/mute &lt;player&gt;</b> - Mute player</i></dd>
<dd><i><b>/unmute &lt;player&gt;</b> - Unmute player</i></dd>
<dd><i><b>/listmuted</b> - Get the list of muted players</i></dd>
<br>

**Permissions:**

- <dd><i><b>chatcensor.*</b> - ChatCensor permission tree.</i></dd>
- <dd><i><b>chatcensor.bypass.*</b> - Bypass ChatCensor features.</i></dd>
- <dd><i><b>chatcensor.bypass.censor</b> - Bypass ChatCensor word censor feature.</i></dd>
- <dd><i><b>chatcensor.bypass.anti-caps</b> - Bypass ChatCensor anti-caps feature.</i></dd>
- <dd><i><b>chatcensor.bypass.char-check</b> - Bypass ChatCensor char-check feature.</i></dd>
- <dd><i><b>chatcensor.bypass.anti-spam</b> - Bypass ChatCensor spam-check feature.</i></dd>
- <dd><i><b>chatcensor.commands.*</b> - ChatCensor commands permission tree.</i></dd>
- <dd><i><b>chatcensor.commands.help</b> - Let player read ChatCensor commands help.</i></dd>
- <dd><i><b>chatcensor.commands.info</b> - Let player read info about ChatCensor.</i></dd>
- <dd><i><b>chatcensor.commands.reload</b> - Let player reload ChatCensor configuration.</i></dd>
- <dd><i><b>chatcensor.commands.addword</b> - Let player add a censored word.</i></dd>
- <dd><i><b>chatcensor.commands.removeword</b> - Let player remove a censored word.</i></dd>
- <dd><i><b>chatcensor.commands.mute</b> - Let player mute a player.</i></dd>
- <dd><i><b>chatcensor.commands.unmute</b> - Let player unmute a player.</i></dd>
- <dd><i><b>chatcensor.commands.listmuted</b> - Let player show the list of muted players.</i></dd>

## API

Almost all our plugins have API access to widely extend their features.

To access ChatCensor API:<br>
*1. Define the plugin dependency in plugin.yml (you can check if ChatCensor is installed in different ways):*

```yaml
depend: [ChatCensor]
```

*2. Include ChatCensor API in your plugin code:*

```php
//ChatCensor API
use ChatCensor\ChatCensor;
```

*3. Access the API by doing:*

```php
ChatCensor::getAPI()
```