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
declare(strict_types=1);

namespace Dotclear\Plugin\auberge;

class FrontendBehaviors
{
    /**
     * Cope with no_first_aside attribute
     *
     * @param      string  $tag    The tag
     * @param      array  $args   The arguments
     */
    public static function publicBeforeContentFilter(string $tag, array $args)
    {
        if (isset($args['no_first_aside']) && (int) $args['no_first_aside'] > 0) {
            if (str_starts_with($args[0], '<aside>')) {
                // Remove first aside if exists at beginning of string
                $args[0] = preg_replace('/<aside>(.*)?<\/aside>/msU', '', $args[0], 1);
            }
        }
    }

    public static function coreBlogGetComments($rs)
    {
        $rs->extend(CommentExtensions::class);
    }
}
