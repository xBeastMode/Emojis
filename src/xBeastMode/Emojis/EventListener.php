<?php
declare(strict_types=1);
namespace xBeastMode\Emojis;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use xBeastMode\Emojis\Entity\EmojiEntity;
use xBeastMode\Emojis\Entity\EmojiModelEntity;
class EventListener implements Listener{
        /** @var Loader */
        protected Loader $loader;

        /** @var bool[] */
        public static array $remove_entity_sessions = [];

        /**
         * EventListener constructor.
         *
         * @param Loader $loader
         */
        public function __construct(Loader $loader){
                $this->loader = $loader;
        }

        /**
         * @param EntityDamageEvent $event
         */
        public function onEntityDamage(EntityDamageEvent $event){
                $entity = $event->getEntity();

                if($entity instanceof EmojiModelEntity && $event instanceof EntityDamageByEntityEvent){
                        $damager = $event->getDamager();

                        if($damager instanceof Player && isset(self::$remove_entity_sessions[spl_object_hash($damager)])){
                                $entity->close();

                                $damager->sendMessage("§aEntity removed.");
                                unset(self::$remove_entity_sessions[spl_object_hash($damager)]);

                                $event->cancel();
                        }
                }

                if($entity instanceof EmojiEntity || $entity instanceof EmojiModelEntity){
                        $event->cancel();
                }
        }

        /**
         * @param PlayerChatEvent $event
         */
        public function onPlayerChat(PlayerChatEvent $event){
                if($this->loader->getEmojiHandler()->handleChat($event->getPlayer(), $event->getMessage())){
                        $event->cancel();
                }
        }

        /**
         * @param PlayerQuitEvent $event
         */
        public function onPlayerQuit(PlayerQuitEvent $event){
                $this->loader->getEmojiHandler()->handleDisconnect($event->getPlayer());
        }
}