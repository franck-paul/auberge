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

use dcCore;
use dcNsProcess;

class Frontend extends dcNsProcess
{
    protected static $init = false; /** @deprecated since 2.27 */
    public static function init(): bool
    {
        static::$init = My::checkContext(My::FRONTEND);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        // Don't do things in frontend if plugin disabled
        $settings = dcCore::app()->blog->settings->get(My::id());
        if (!(bool) $settings->active) {
            return false;
        }

        // Public behaviours
        dcCore::app()->addBehaviors([
            'coreBlogGetComments' => [FrontendBehaviors::class, 'coreBlogGetComments'],

            'publicBeforeContentFilterV2' => [FrontendBehaviors::class, 'publicBeforeContentFilter'],
        ]);

        // Public template tags
        dcCore::app()->tpl->addValue('AuthorRoom', [FrontendTemplate::class, 'authorRoom']);
        dcCore::app()->tpl->addValue('AuthorRoomClass', [FrontendTemplate::class, 'authorRoomClass']);
        dcCore::app()->tpl->addValue('AuthorCheckIn', [FrontendTemplate::class, 'authorCheckIn']);
        dcCore::app()->tpl->addValue('AuthorCheckOut', [FrontendTemplate::class, 'AuthorCheckOut']);
        dcCore::app()->tpl->addValue('AuthorCheckStays', [FrontendTemplate::class, 'AuthorCheckStays']);
        dcCore::app()->tpl->addValue('CommentIfEven', [FrontendTemplate::class, 'CommentIfEven']);
        dcCore::app()->tpl->addValue('PingIfEven', [FrontendTemplate::class, 'PingIfEven']);
        dcCore::app()->tpl->addValue('BlogShortname', [FrontendTemplate::class, 'BlogShortname']);
        dcCore::app()->tpl->addValue('TagLabel', [FrontendTemplate::class, 'TagLabel']);

        return true;
    }
}
