<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Event;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;
use xBeastMode\Emojis\Emoji;
class EmojiEvent extends PluginEvent implements Cancellable{
        use CancellableTrait;

        /** @var Emoji */
        protected Emoji $emoji;

        /**
         * EmojiEvent constructor.
         *
         * @param Plugin $plugin
         * @param Emoji  $emoji
         */
        public function __construct(Plugin $plugin, Emoji $emoji){
                parent::__construct($plugin);

                $this->emoji = $emoji;
        }

        /**
         * @return Emoji
         */
        public function getEmoji(): Emoji{
                return $this->emoji;
        }

        /**
         * @param Emoji $emoji
         */
        public function setEmoji(Emoji $emoji): void{
                $this->emoji = $emoji;
        }
}