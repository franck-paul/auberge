<?php
/**
 * @brief auberge, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class aubergeUtils
{
    private static $loaded = false;
    private static $var_path;
    private static $tag_labels;
    private static $id_positions;

    private static function load()
    {
        if (self::$loaded) {
            return;
        }

        self::$var_path = path::real(DC_VAR) . '/blogs/auberge/';
        files::makeDir(self::$var_path, true);

        // Load tag → label data
        $file             = self::$var_path . 'tags.json';
        self::$tag_labels = [];
        if (file_exists($file)) {
            $content = @file_get_contents($file);
            if ($content) {
                self::$tag_labels = json_decode($content, true);
            }
        }

        // Load id → position data
        $file               = self::$var_path . 'positions.json';
        self::$id_positions = [];
        if (file_exists($file)) {
            $content = @file_get_contents($file);
            if ($content) {
                self::$id_positions = json_decode($content, true);
            }
        }

        self::$loaded = true;
    }

    public static function getTagLabel($tag)
    {
        self::load();
        if (isset(self::$tag_labels[$tag])) {
            return self::$tag_labels[$tag];
        }

        return $tag;
    }

    public static function getIdPosition($id, $default = '')
    {
        self::load();
        if (isset(self::$id_positions[$id])) {
            return self::$id_positions[$id];
        }

        return $default;
    }
}
