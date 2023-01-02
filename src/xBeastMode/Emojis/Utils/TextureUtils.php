<?php
declare(strict_types=1);
namespace xBeastMode\Emojis\Utils;
use xBeastMode\Emojis\Loader;
class TextureUtils{
        /**
         * @param string $name
         *
         * @return null|string
         */
        public static function getTexture(string $name): ?string{
                return static::textureFromPNGFile(Loader::getInstance()->getDataFolder() . "/Textures/$name.png");
        }

        /**
         * @param string $name
         *
         * @return bool|string
         */
        public static function getGeometryData(string $name = "emote.json"): bool|string{
                return file_get_contents(Loader::getInstance()->getDataFolder() . "/Textures/$name");
        }

        /**
         * @param string $path
         *
         * @return null|string
         */
        public static function textureFromPNGFile(string $path): ?string{
                $img = @imagecreatefrompng($path);
                $height = (int) @getimagesize($path)[1];

                $texture_bytes = "";

                for ($y = 0; $y < $height; $y++) {
                        for ($x = 0; $x < 64; $x++) {
                                $argb = @imagecolorat($img, $x, $y);
                                $a = ((~($argb >> 24)) << 1) & 0xff;
                                $r = ($argb >> 16) & 0xff;
                                $g = ($argb >> 8) & 0xff;
                                $b = $argb & 0xff;
                                $texture_bytes .= TextureUtils . phpchr($r) . chr($b) . chr($a);
                        }
                }

                @imagedestroy($img);
                return $texture_bytes;
        }
}