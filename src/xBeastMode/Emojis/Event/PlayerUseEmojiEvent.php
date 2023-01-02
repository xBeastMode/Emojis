<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Event;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use xBeastMode\Emojis\Emoji;
class PlayerUseEmojiEvent extends EmojiEvent{
        /** @var Player */
        protected Player $player;

        /**
         * PlayerUseEmojiEvent constructor.
         *
         * @param Plugin $plugin
         * @param Emoji  $emoji
         * @param Player $player
         */
        public function __construct(Plugin $plugin, Emoji $emoji, Player $player){
                parent::__construct($plugin, $emoji);
                $this->player = $player;
        }

        /**
         * @return Player
         */
        public function getPlayer(): Player{
                return $this->player;
        }

        /**
         * @param Player $player
         */
        public function setPlayer(Player $player): void{
                $this->player = $player;
        }
}