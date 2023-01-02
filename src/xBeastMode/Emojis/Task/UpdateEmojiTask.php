<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Task;
use pocketmine\scheduler\Task;
use xBeastMode\Emojis\Loader;
class UpdateEmojiTask extends Task{
        /** @var Loader */
        protected Loader $loader;

        /**
         * UpdateEmojiTask constructor.
         *
         * @param Loader $loader
         */
        public function __construct(Loader $loader){
                $this->loader = $loader;
        }

        public function onRun(): void{
                $this->loader->getEmojiHandler()->update();
        }
}