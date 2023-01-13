# Emojis v1.0.0
<div style="text-align: center;">
<img src="https://github.com/xBeastMode/Emojis/raw/main/icon.png" alt="">
</div>

by xBeastMode
# Feature
- [x] Use emojis via chat
- [x] Spawn emoji models
- [x] Create custom emojis
- [x] Disable emojis per world
- [x] Option to enable use permission
- [x] Duration and max stack limiter
- [ ] Use emojis via modal forms
- [ ] Use emojis via commands

# Usage

Simply send your desired emoji's name in chat, you may use multiple emojis
in one message, you can also set the duration of the emoji in seconds by separating it with dot "."
for example including "cool.10" in your chat message will activate the "cool" emoji for 10 seconds.
The time separation character is configurable as well as the default and max duration time.

**NOTE**: Use permissions are required by default, but can be disabled in config, 
by setting `requires-permission` to `false`.<br>
**Permission node**: 
`emojis.emoji.[name]`, e.g. `emojis.emoji.joy` for a single emoji 
**OR** `emojis.emoji.all` for all emojis.


The default emoji list includes:
- blush
- cool
- cry
- heart
- heart_eyes
- joy
- kiss
- pensive
- pepe
- pepegang
- pog
- sad
- smile
- smile_hearts
- smirk
- sweat
- tongue
- tongue_wink
- unamused
- wink
- worried

# Screenshot

<img src="https://github.com/xBeastMode/Emojis/raw/main/screenshot.png" alt="">

# Commands

- `/spawnemojimodel`
    - permission: `command.spawnemojimodel`
    - usage: `/spawnemojimodel <name> [text...]`

the `/spawnemojimodel` command as the name suggests spawns a emoji model
with the desired name. To remove the model once spawned simply use `/spawnemojimodel remove`
and tap the model to remove it.

**NOTE**: if emoji uses custom geometry, the emoji will not render as expected
because the command uses the default emoji geometry 
for which I suggest you write your own model spawn code if you wish to spawn custom emoji models.

# Configuration
```yaml
---
---
# ====================================================
# default list of emojis
# ====================================================
# blush, cool, cry, heart, heart_eyes, joy, kiss, pensive
# pepe, pepegang, pog, sad, smile, smile_hearts, smirk, sweat,
# tongue, tongue_wink, unamused, wink, worried
# ====================================================

# ====================================================
# This is an array containing the names of the emojis
# ====================================================
# you want to disable. The format should go as such:
# disabled-emojis: [blush, cool, etc...]
# ====================================================
disabled-emojis: []

# ====================================================
# This is an array containing the names of the worlds
# you want to disable.
# ====================================================
# To disable all emojis in a specific
# world the format should go as such:
# disabled-worlds:
#   world: "*"
# ====================================================
# To disable specific emojis in a specific
# world the format should go as such:
# disabled-worlds:
#   world: [blush, cool, etc...]
# ====================================================
disabled-worlds: []

# ====================================================
# This is an array containing the names of additional
# 3rd party emojis you may want to add outside of
# default emojis
# ====================================================
# To add new emojis simply drop your new emoji's texture
# in the "plugin_data/Emojis/textures" folder and add
# the name inside the array
# =====================================================
# NOTE: textures must be in 64x64 PNG 32-bit color format
# if the emoji uses the default geometry
# =====================================================
additional-emojis: []

# ====================================================
# Set this to "true" if you want to require player's to have
# permission to use emojis, otherwise set this to "false"
# ====================================================
requires-permission: true

# ====================================================
# This is the character that separates the emoji name
# and duration time in the emoji chat format.
# ====================================================
# For example if the separator is "." and I chat "cool.5"
# then the emoji will be "cool" and have a 5 second duration.
# ====================================================
# This can also have multiple characters or have other
# characters than a dot for example "::" and that would be
# used as such: "cool::5"
# ====================================================
time-separator: "."

# ====================================================
# This is the default duration of an emoji
# if not provided by the player
# ====================================================
default-duration: 5

# ====================================================
# Set this to "true" if you want to enable an active emoji
# limit and set that limit in the "max-emojis" value
# ====================================================
limit-emojis: true

# ====================================================
# This is the limit of emojis a player can have active at once
# ====================================================
max-emojis: 3

# ====================================================
# Set this to "true" if you want to enable an emoji duration
# limit and set that duration in the "max-duration" value
# ====================================================
limit-duration: true

# ====================================================
# This is the maximum duration of an emoji in seconds
# ====================================================
max-duration: 10

# ====================================================
# Set this to "true" if you want to cancel messages
# when players use emojis via chat
# ====================================================
cancel-chat: false
...
```

# API

**To register new emojis** via the api simply drop 
your new emoji's texture in your `plugin_data/Emojis/textures`
folder. Textures must be in 64x64 PNG 32-bit color format if using default geometry.
Say new emojis name is `watermelon`,
the file must be named `watermelon.png`.
Now to register it use:
```php
\xBeastMode\Emojis\EmojiManager::getInstance()
->getEmojiManager()
->registerEmoji("watermelon")
```
You may optionally provide a second argument with a 
custom extension of the `\xBeastMode\Emojis\Emoji`
class. This allows you to do more stuff with the emoji's
entity and create your own animations.

**To get an emoji (class)** use
```php
\xBeastMode\Emojis\EmojiManager::getInstance()
->getEmojiManager()
->getEmoji("watermelon")
```
this returns an instance of `\xBeastMode\Emojis\Emoji`.

**To activate an emoji** for a player simply pass the player
and emoji class to this function:
```php
\xBeastMode\Emojis\EmojiManager::getInstance()
->getEmojiManager()
->addActiveEmoji($player, $emoji)
```

If you wanna learn more of the api I encourage you to explore
around the code, it's quite a simple plugin really with little functions
and features, so I'm hoping other devs are encouraged to write
"addons" for this plugin as I believe is a fun concept. Enjoy!