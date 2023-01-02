<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
class EmojiEntity extends Human{
        /** @var bool */
        protected $gravityEnabled = false;

        /**
         * @param Location         $location
         * @param Skin             $skin
         * @param CompoundTag|null $nbt
         */
        public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null){
                parent::__construct($location, $skin, $nbt);

                $this->setCanSaveWithChunk(false);
        }

        /**
         * @param Vector3 $vector3
         * @param float   $yaw
         */
        public function moveTo(Vector3 $vector3, float $yaw): void{
                $this->location->x = $vector3->x;
                $this->location->y = $vector3->y;
                $this->location->z = $vector3->z;
                $this->location->yaw = $yaw;
                $this->updateMovement();
        }
}