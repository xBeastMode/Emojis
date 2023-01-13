<?php
declare(strict_types=1);
namespace xBeastMode\Emojis;
use pocketmine\entity\EntityDataHelper as Helper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Filesystem;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use xBeastMode\Emojis\Commands\SpawnEmojiModelCommand;
use xBeastMode\Emojis\Entity\EmojiModelEntity;
use xBeastMode\Emojis\Task\UpdateEmojiTask;
class Loader extends PluginBase{
        use SingletonTrait;

        /** @var EmojiManager|null */
        protected ?EmojiManager $emoji_manager = null;

        /** @var EmojiHandler|null */
        protected ?EmojiHandler $emoji_handler = null;

        public function onEnable(): void{
                self::setInstance($this);

                $this->saveTextures();

                $this->emoji_manager = new EmojiManager($this);
                $this->emoji_handler = new EmojiHandler($this);

                $this->getScheduler()->scheduleRepeatingTask(new UpdateEmojiTask($this), 0);
                $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

                $this->getServer()->getCommandMap()->register("emojis", new SpawnEmojiModelCommand($this));

                EntityFactory::getInstance()->register(EmojiModelEntity::class, function(World $world, CompoundTag $nbt) : EmojiModelEntity{
                        return new EmojiModelEntity(Helper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
                }, ["EmojiModelEntity"]);
        }

        public function onDisable(): void{
                $this->emoji_handler?->close();
        }

        public function saveTextures(){
                Filesystem::recursiveCopy(__DIR__ . "/Textures", $this->getDataFolder() . "textures/");
        }

        /**
         * @return EmojiManager|null
         */
        public function getEmojiManager(): ?EmojiManager{
                return $this->emoji_manager;
        }

        /**
         * @param EmojiManager|null $emoji_manager
         *
         * @return Loader
         */
        public function setEmojiManager(?EmojiManager $emoji_manager): self{
                $this->emoji_manager = $emoji_manager;
                return $this;
        }

        /**
         * @return EmojiHandler|null
         */
        public function getEmojiHandler(): ?EmojiHandler{
                return $this->emoji_handler;
        }

        /**
         * @param EmojiHandler|null $emoji_handler
         *
         * @return Loader
         */
        public function setEmojiHandler(?EmojiHandler $emoji_handler): self{
                $this->emoji_handler = $emoji_handler;
                return $this;
        }

        /**
         * @return Permission|null
         */
        public static function getOperatorPermission(): ?Permission{
                return PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
        }
}