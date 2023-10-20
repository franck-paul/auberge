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

use Dotclear\Schema\Extension\Comment;

class CommentExtensions extends Comment
{
    /**
     * Returns whether comment is from the post author.
     *
     * @param      mixed   $rs     Invisible parameter
     *
     * @return     bool    True if the specified rs is me, False otherwise.
     */
    public static function isMe($rs): bool
    {
        return
        $rs->comment_email && $rs->comment_email == $rs->user_email;
    }
}
