<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Event;
use pocketmine\plugin\Plugin;
use pocketmine\world\Position;
use xBeastMode\Emojis\Emoji;
class EmojiUpdatePositionEvent extends EmojiEvent{
        /** @var Position */
        protected Position $position;

        /**
         * PlayerUseEmojiEvent constructor.
         *
         * @param Plugin   $plugin
         * @param Emoji    $emoji
         * @param Position $position
         */
        public function __construct(Plugin $plugin, Emoji $emoji, Position $position){
                parent::__construct($plugin, $emoji);
                $this->position = $position;
        }

        /**
         * @return Position
         */
        public function getPosition(): Position{
                return $this->position;
        }

        /**
         * @param Position $position
         */
        public function setPosition(Position $position): void{
                $this->position = $position;
        }
}