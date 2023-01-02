<?php
declare(strict_types=1);
namespace xBeastMode\Emojis;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\player\Player;
class EmojiManager{
        /** @var string[] */
        protected array $emoji_factory = [];
        /** @var Emoji[][][]|Player[][][] */
        protected array $active_emojis = [];

        /** @var Emoji[] */
        protected array $emoji_cache = [];
        /** @var Loader */
        protected Loader $loader;

        /**
         * EmojiManager constructor.
         *
         * @param Loader $loader
         */
        public function __construct(Loader $loader){
                $this->loader = $loader;

                $default_emoji_names = [
                    "blush",
                    "cool",
                    "cry",
                    "heart",
                    "heart_eyes",
                    "joy",
                    "kiss",
                    "pensive",
                    "pepe",
                    "pepegang",
                    "pog",
                    "sad",
                    "smile",
                    "smile_hearts",
                    "smirk",
                    "sweat",
                    "tongue",
                    "tongue_wink",
                    "unamused",
                    "wink",
                    "worried"
                ];

                $default_emoji_names = array_merge($default_emoji_names, $loader->getConfig()->get("additional-emojis", []));
                $disabled_emojis = $loader->getConfig()->get("disabled-emojis", []);

                foreach($default_emoji_names as $emoji_name){
                        if(!in_array($emoji_name, $disabled_emojis)){
                                $this->registerEmoji($emoji_name);
                        }
                }
        }

        /**
         * @return string[]
         */
        public function getAllEmojiNames(): array{
                return array_keys($this->emoji_factory);
        }

        /**
         * @param string $name
         *
         * @return bool
         */
        public function isEmojiRegistered(string $name): bool{
                return isset($this->emoji_factory[$name]);
        }

        /**
         * @param string $name
         * @param string $emoji_class
         *
         * @return bool
         */
        public function registerEmoji(string $name, string $emoji_class = Emoji::class): bool{
                $is_emoji_registered = $this->isEmojiRegistered($name);
                $this->emoji_factory[$name] = $emoji_class;

                if($this->loader->getConfig()->get("requires-permission", true)){
                        $permission = $this->loader->getConfig()->get("permission-format", "emoji.{emoji}");
                        $permission = str_replace("{emoji}", $name, $permission);

                        DefaultPermissions::registerPermission(new Permission($permission, ""), [Loader::getOperatorPermission()]);
                }
                return !$is_emoji_registered;
        }

        /**
         * @param string $name
         *
         * @return bool
         */
        public function unregisterEmoji(string $name): bool{
                $is_emoji_registered = $this->isEmojiRegistered($name);
                unset($this->emoji_factory[$name]);

                return $is_emoji_registered;
        }

        /**
         * @param string $name
         * @param string|null $texture
         * @param string|null $geometry_data
         * @param int|null $max_life_ticks
         * @param bool|null $despawn_animation_enabled
         * @param int|null $despawn_animation_duration_ticks
         *
         * @return Emoji|null
         */
        public function getEmoji(
                string $name,
                ?string $texture = null,
                ?string $geometry_data = null,
                ?int $max_life_ticks = null,
                ?bool $despawn_animation_enabled = null,
                ?int $despawn_animation_duration_ticks = null
        ): ?Emoji{
                if($this->isEmojiRegistered($name)){
                        /** @var Emoji $emoji */
                        $emoji = new $this->emoji_factory[$name](
                                $name,
                                $texture,
                                $geometry_data,
                                $max_life_ticks,
                                $despawn_animation_enabled,
                                $despawn_animation_duration_ticks
                        );
                        return $this->emoji_cache[$emoji->getRuntimeId()] = $emoji;
                }
                return null;
        }

        /**
         * @param int $runtime_id
         *
         * @return null|Emoji
         */
        public function getEmojiByRuntimeId(int $runtime_id): ?Emoji{
                return $this->emoji_cache[$runtime_id] ?? null;
        }

        /**
         * @param Player $player
         * @param Emoji  $emoji
         */
        public function addActiveEmoji(Player $player, Emoji $emoji): void{
                $this->active_emojis[spl_object_hash($player)][$emoji->getRuntimeId()] = [$emoji, $player];
        }

        /**
         * @param Player $player
         * @param Emoji  $emoji
         */
        public function removeActiveEmoji(Player $player, Emoji $emoji): void{
                unset($this->active_emojis[spl_object_hash($player)][$emoji->getRuntimeId()]);
        }

        /**
         * @param null|Player $player
         *
         * @return array
         */
        public function getActiveEmojis(?Player $player = null): array{
                return $player !== null ? $this->active_emojis[spl_object_hash($player)] ?? [] : $this->active_emojis;
        }
}