<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Commands;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\InvalidSkinException;
use pocketmine\entity\Skin;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use xBeastMode\Emojis\Entity\EmojiModelEntity;
use xBeastMode\Emojis\EventListener;
use xBeastMode\Emojis\Loader;
use xBeastMode\Emojis\Utils\TextureUtils;
class SpawnEmojiModelCommand extends Command implements PluginOwned{
        public function __construct(public Loader $plugin){
                parent::__construct("spawnemojimodel", "Spawns emoji model", "Usage: /spawnemojimodel <name> [text...]", ["sem"]);
                $this->setPermission("emojis.command.spawnemojimodel");
        }

        /**
         * @param CommandSender $sender
         * @param string        $commandLabel
         * @param array         $args
         *
         * @return bool
         */
        public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
                if(!$this->testPermission($sender)){
                        return false;
                }

                if(!$sender instanceof Player){
                        $sender->sendMessage("Please run this command in-game.");
                        return false;
                }

                if(count($args) <= 0){
                        $sender->sendMessage($this->getUsage());
                        return false;
                }

                if(strtolower($args[0]) === "remove"){
                        EventListener::$remove_entity_sessions[spl_object_hash($sender)] = true;
                        $sender->sendMessage("§aHit emoji model to remove it.");
                        return true;
                }

                $name = array_shift($args);
                $emoji_list = $this->plugin->getEmojiManager()->getAllEmojiNames();

                if(!in_array($name, $emoji_list)){
                        $sender->sendMessage("§eNo emoji with such name.");
                        $sender->sendMessage("§eKnown emoji list: §f" . implode(", ", $emoji_list));
                        return true;
                }

                $text = empty($args) ? $name : implode(" ", $args);

                $texture = TextureUtils::getTexture($name);
                $geometry = TextureUtils::getGeometryData();

                try{
                        $skin = new Skin("Standard_Custom", $texture, "", "geometry.emote", $geometry);
                        $emoji = new EmojiModelEntity($sender->getLocation(), $skin);

                        $emoji->setNameTag(str_replace("{line}", "\n", $text));
                        $emoji->spawnToAll();
                }catch(\JsonException|InvalidSkinException $exception){
                        $sender->getServer()->getLogger()->error($exception->getMessage());

                }

                return true;
        }

        public function getOwningPlugin(): Plugin{
                return $this->plugin;
        }
}