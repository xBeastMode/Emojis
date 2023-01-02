<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;
class EmojiModelEntity extends Human{
        /**
         * @param Location         $location
         * @param Skin             $skin
         * @param CompoundTag|null $nbt
         */
        public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null){
                parent::__construct($location, $skin, $nbt);

                $this->setNameTagVisible();
                $this->setNameTagAlwaysVisible();
        }

        /**
         * @param int $tickDiff
         *
         * @return bool
         */
        protected function entityBaseTick(int $tickDiff = 1): bool{
                $entity_yaw = $this->location->yaw;
                $entity_yaw = ($entity_yaw >= 360 ? 0 : $entity_yaw + 10);

                $this->location->yaw = $entity_yaw;
                return parent::entityBaseTick($tickDiff);
        }
}