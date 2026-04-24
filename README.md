# 😀 Emojis v1.0.0

<p align="center">
  <img src="https://github.com/xBeastMode/Emojis/raw/main/icon.png" alt="Emojis Icon">
</p>

A PocketMine-MP plugin that allows players to **use animated emojis in-game** via chat or commands, enhancing communication and expression.

---

## ✨ Features

- 💬 Use emojis directly in chat  
- 🎭 Spawn emoji models in the world  
- 🧩 Support for custom emojis  
- 🌍 Disable emojis per world  
- 🔐 Optional permission-based usage  
- ⏱️ Configurable duration and limits  
- 🚧 Planned:
  - Modal form support  
  - Command-based emoji usage  

---

## 🚀 Usage

To use an emoji, simply type its name in chat.

### Example:
```
cool
```

You can also specify duration using a separator:

```
cool.10
```

This will display the `cool` emoji for **10 seconds**.

- The separator (`.` by default) is configurable  
- Multiple emojis can be used in one message  

---

## 🔐 Permissions

By default, permissions are required.

- `emojis.emoji.<name>` → Use a specific emoji  
- `emojis.emoji.all` → Use all emojis  

You can disable permission checks in the config:

```yaml
requires-permission: false
```

---

## 🎭 Default Emojis

```
blush, cool, cry, heart, heart_eyes, joy, kiss, pensive,
pepe, pepegang, pog, sad, smile, smile_hearts, smirk,
sweat, tongue, tongue_wink, unamused, wink, worried
```

---

## 📸 Screenshot

<p align="center">
  <img src="https://github.com/xBeastMode/Emojis/raw/main/screenshot.png" alt="Screenshot">
</p>

---

## 🧾 Commands

### `/spawnemojimodel`

Spawn or remove emoji models in the world.

**Usage:**
```
/spawnemojimodel <name> [text...]
/spawnemojimodel remove
```

**Permission:**
```
command.spawnemojimodel
```

**Notes:**
- Tap a model after using `remove` to delete it  
- Custom geometry may not render correctly with this command  
- For advanced models, custom spawning logic is recommended  

---

## ⚙️ Configuration

```yaml
# Emojis to disable globally
disabled-emojis: []

# Disable emojis per world
disabled-worlds: []

# Add custom emojis (textures must be 64x64 PNG)
additional-emojis: []

# Require permissions
requires-permission: true

# Separator for duration (e.g. cool.5)
time-separator: "."

# Default duration (seconds)
default-duration: 5

# Limit active emojis
limit-emojis: true
max-emojis: 3

# Limit duration
limit-duration: true
max-duration: 10

# Cancel chat message when using emojis
cancel-chat: false
```

---

## 🧩 API

### Register an Emoji

```php
\xBeastMode\Emojis\EmojiManager::getInstance()
    ->getEmojiManager()
    ->registerEmoji("watermelon");
```

You can optionally pass a custom class extending:

```
\xBeastMode\Emojis\Emoji
```

---

### Get an Emoji

```php
$emoji = \xBeastMode\Emojis\EmojiManager::getInstance()
    ->getEmojiManager()
    ->getEmoji("watermelon");
```

---

### Activate an Emoji

```php
\xBeastMode\Emojis\EmojiManager::getInstance()
    ->getEmojiManager()
    ->addActiveEmoji($player, $emoji);
```

---

## 🧪 Learning Outcomes

This project demonstrates:

- Event-driven plugin development in PHP  
- Working with PocketMine-MP APIs  
- Entity/model manipulation  
- Designing extensible plugin systems  
- Handling real-time player interactions  

---

## 💡 Notes

- Custom emoji textures must be:
  - **64x64 PNG**
  - **32-bit color**
- Place textures in:
  ```
  plugin_data/Emojis/textures/
  ```

---

## 🤝 Contributing

Feel free to fork the project and build your own extensions or addons.
