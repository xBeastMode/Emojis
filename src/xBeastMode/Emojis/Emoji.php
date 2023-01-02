<?php
declare(strict_types=1);
namespace xBeastMode\Emojis;
use pocketmine\entity\Entity;
use pocketmine\entity\InvalidSkinException;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\world\Position;
use pocketmine\Server;
use xBeastMode\Emojis\Entity\EmojiEntity;
use xBeastMode\Emojis\Utils\TextureUtils;
class Emoji{
        public const DEFAULT_MAX_LIFE_TICKS = 100;
        public const DEFAULT_DESPAWN_ANIMATION_DURATION_TICKS = 40;

        /** @var int */
        protected static int $emoji_count = 0;

        /** @var int */
        protected int $runtime_id;

        /** @var string */
        public string $geometry_name = "geometry.emote";

        /** @var EmojiEntity|null */
        protected ?EmojiEntity $entity_instance = null;

        /**
         * Emoji constructor.
         *
         * @param string $name
         * @param string|null $texture
         * @param string|null $geometry_data
         * @param int|null $max_life_ticks
         * @param bool|null $despawn_animation_enabled
         * @param int|null $despawn_animation_duration_ticks
         */
        public function __construct(
                public string $name,
                public ?string $texture = null,
                public ?string $geometry_data = null,
                public ?int $max_life_ticks = null,
                public ?bool $despawn_animation_enabled = null,
                public ?int $despawn_animation_duration_ticks = null
        ){
                $this->texture = $this->texture ?? TextureUtils::getTexture($this->name) ?? str_repeat("\x00", 64 * 64 * 4);
                $this->geometry_data = $this->geometry_data ?? TextureUtils::getGeometryData();
                $this->max_life_ticks = $this->max_life_ticks ?? static::DEFAULT_MAX_LIFE_TICKS;
                $this->despawn_animation_enabled = $this->despawn_animation_enabled ?? true;
                $this->despawn_animation_duration_ticks = $this->despawn_animation_duration_ticks ?? static::DEFAULT_DESPAWN_ANIMATION_DURATION_TICKS;

                $this->runtime_id = ++self::$emoji_count;
        }

        /**
         * @return string
         */
        public function getName(): string{
                return $this->name;
        }

        /**
         * @return int
         */
        public function getRuntimeId(): int{
                return $this->runtime_id;
        }

        /**
         * @return Skin|null
         */
        public function getSkin(): ?Skin{
                try{
                        return new Skin("Standard_Custom", $this->texture, "", $this->geometry_name, $this->geometry_data);
                }catch(\JsonException|InvalidSkinException $exception){
                        Server::getInstance()->getLogger()->error($exception->getMessage());
                }
                return null;
        }

        /**
         * @return int
         */
        public function getTicksLived(): int{
                return $this->entity_instance instanceof Entity ? $this->entity_instance->ticksLived : 0;
        }

        /**
         * @return int
         */
        public function getMaxLifeTicks(): int{
                return $this->max_life_ticks;
        }

        /**
         * @return EmojiEntity|null
         */
        public function getEntityInstance(): ?EmojiEntity{
                return $this->entity_instance;
        }

        public function spawn(): void{
                $this->entity_instance->spawnToAll();
        }

        public function close(): void{
                $this->entity_instance->close();
        }

        /**
         * @return float
         */
        public function getScale(): float{
                return $this->entity_instance !== null ? $this->entity_instance->getScale() : 1;
        }

        /**
         * @return bool
         */
        public function expired(): bool{
                return ($this->getTicksLived() - $this->despawn_animation_duration_ticks) >= $this->getMaxLifeTicks();
        }

        /**
         * @param bool $force
         */
        public function doDespawnAnimation(bool $force = false): void{
                $scale = $this->entity_instance->getScale() - (0.5 / $this->despawn_animation_duration_ticks);
                if(($this->despawn_animation_enabled or $force) and $scale > 0.001){
                        $this->entity_instance->setScale($scale);

                }
        }

        /**
         * @param Position $position
         */
        public function update(Position $position): void{
                if($this->entity_instance === null){
                        $this->createEntityInstance();

                        $this->entity_instance->teleport($position);
                        $this->entity_instance->setForceMovementUpdate();

                        $this->spawn();
                }

                $entity_yaw = $this->entity_instance->getLocation()->yaw;
                $entity_yaw = ($entity_yaw >= 360 ? 0 : $entity_yaw + 10);

                $this->entity_instance->moveTo($position, $entity_yaw);
        }

        public function createEntityInstance(): void{
                if($this->entity_instance instanceof Entity){
                        $this->entity_instance->close();
                }

                $world = Server::getInstance()->getWorldManager()->getDefaultWorld();
                try{
                        $this->entity_instance = new EmojiEntity(new Location(0, 0, 0, $world, 0, 0), $this->getSkin());
                }catch(InvalidSkinException $exception){
                        Server::getInstance()->getLogger()->error($exception->getMessage());
                }
        }
}