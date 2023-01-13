<?php
declare(strict_types=1);
namespace xBeastMode\Emojis;
use pocketmine\player\Player;
use pocketmine\world\Position;
use xBeastMode\Emojis\Event\EmojiUpdatePositionEvent;
use xBeastMode\Emojis\Event\PlayerUseEmojiEvent;
class EmojiHandler{
        /** @var Loader */
        protected Loader $loader;

        /**
         * EmojiHandler constructor.
         *
         * @param Loader $loader
         */
        public function __construct(Loader $loader){
                $this->loader = $loader;
        }

        public function close(): void{
                $emoji_manager = $this->loader->getEmojiManager();

                foreach($emoji_manager->getActiveEmojis() as $index => $emoji_data){
                        foreach($emoji_data as $runtime_id => $emojis){
                                /** @var Emoji $emoji */
                                $emoji = $emojis[0];
                                /** @var Player $player */
                                $player = $emojis[1];

                                $emoji->close();
                                $emoji_manager->removeActiveEmoji($player, $emoji);
                        }
                }
        }

        /**
         * @param Player $player
         * @param string $message
         *
         * @return bool
         */
        public function handleChat(Player $player, string $message): bool{
                $arguments = explode(" ", $message);
                $output = false;

                $config = $this->loader->getConfig();
                $emoji_manager = $this->loader->getEmojiManager();

                $limit_emojis = $config->get("limit-emojis", true);
                $max_emojis = $config->get("max-emojis", 3);

                if($limit_emojis && count($emoji_manager->getActiveEmojis($player)) < $max_emojis){
                        foreach($arguments as $argument){
                                if($limit_emojis && count($emoji_manager->getActiveEmojis($player)) >= $max_emojis) continue;
                                $parts = explode(".", $argument);

                                $limit_duration = $config->get("limit-duration", true);
                                $max_duration = $config->get("max-duration", 10);

                                $name = $parts[0];
                                $duration = $parts[1] ?? 5;
                                $duration = (int) $duration;

                                $duration = $limit_duration ? min($duration, $max_duration) : $duration;

                                $permission = str_replace("{emoji}", $name, EmojiManager::DEFAULT_PERMISSION_NODE);
                                $requires_permission = $config->get("requires-permission", true);

                                if($requires_permission && !$player->hasPermission($permission) && !$player->hasPermission("emojis.emoji.all")) continue;

                                $world_name = $player->getWorld()->getDisplayName();
                                $disabled_worlds = $config->get("disabled-worlds", []);

                                $disabled_in_world = $disabled_worlds[$world_name] ?? null;

                                if(isset($disabled_in_world) && $disabled_in_world === "*") continue;
                                if(isset($disabled_in_world) && is_array($disabled_in_world) && in_array($name, $disabled_in_world)) continue;

                                if($emoji_manager->isEmojiRegistered($name)){
                                        $emoji = $emoji_manager->getEmoji($name, max_life_ticks: $duration * 20);

                                        $event = new PlayerUseEmojiEvent($this->loader, $emoji, $player);
                                        $event->call();

                                        if($event->isCancelled()) continue;

                                        $emoji_manager->addActiveEmoji($event->getPlayer(), $event->getEmoji());
                                        $output = $config->get("cancel-chat", true);
                                }
                        }
                }
                return $output;
        }

        /**
         * @param Player $player
         */
        public function handleDisconnect(Player $player): void{
                foreach($this->loader->getEmojiManager()->getActiveEmojis($player) as $active_emoji){
                        /** @var Emoji $emoji */
                        $emoji = $active_emoji[0];

                        $emoji->close();
                        $this->loader->getEmojiManager()->removeActiveEmoji($player, $emoji);
                }
        }

        /**
         * @param Emoji $emoji
         */
        public function doEmojiDespawnAnimation(Emoji $emoji): void{
                if(($emoji->getTicksLived() + $emoji->despawn_animation_duration_ticks) > $emoji->getMaxLifeTicks()){
                        $emoji->doDespawnAnimation();
                }
        }

        public function update(): void{
                $emoji_manager = $this->loader->getEmojiManager();

                foreach($emoji_manager->getActiveEmojis() as $index => $emoji_data){
                        if(count($emoji_data) === 0) continue;

                        /** @var Position $last_position */
                        $last_position = null;
                        /** @var Emoji $last_emoji */
                        $last_emoji = null;

                        foreach($emoji_data as $runtime_id => $emojis){
                                /** @var Emoji $emoji */
                                $emoji = $emojis[0];
                                /** @var Player $player */
                                $player = $emojis[1];

                                if($last_position !== null){
                                        $last_position->y -= 0.8;
                                }

                                $last_emoji_scale = 1;
                                if($last_emoji !== null){
                                        $last_emoji_scale = $last_emoji->getScale();
                                }

                                $last_position = $last_position ?? $player->getPosition();
                                $last_position->y += 2 - (1 - $last_emoji_scale);

                                $last_emoji = $emoji;

                                $event = new EmojiUpdatePositionEvent($this->loader, $emoji, $last_position);
                                $event->call();

                                if($event->isCancelled()) continue;

                                $last_emoji = $emoji = $event->getEmoji();
                                $last_position = $event->getPosition();

                                $emoji->update($last_position);
                                $this->doEmojiDespawnAnimation($emoji);

                                if($emoji->expired()){
                                        $emoji->close();
                                        $emoji_manager->removeActiveEmoji($player, $emoji);
                                }
                        }
                }
        }
}