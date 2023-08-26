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
use Dotclear\Core\Process;

class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // Don't do things in frontend if plugin disabled
        $settings = My::settings();
        if (!(bool) $settings->active) {
            return false;
        }

        // Public behaviours
        dcCore::app()->addBehaviors([
            'coreBlogGetComments' => FrontendBehaviors::coreBlogGetComments(...),

            'publicBeforeContentFilterV2' => FrontendBehaviors::publicBeforeContentFilter(...),
        ]);

        // Public template tags
        dcCore::app()->tpl->addValue('AuthorRoom', FrontendTemplate::authorRoom(...));
        dcCore::app()->tpl->addValue('AuthorRoomClass', FrontendTemplate::authorRoomClass(...));
        dcCore::app()->tpl->addValue('AuthorCheckIn', FrontendTemplate::authorCheckIn(...));
        dcCore::app()->tpl->addValue('AuthorCheckOut', FrontendTemplate::AuthorCheckOut(...));
        dcCore::app()->tpl->addValue('AuthorCheckStays', FrontendTemplate::AuthorCheckStays(...));
        dcCore::app()->tpl->addValue('CommentIfEven', FrontendTemplate::CommentIfEven(...));
        dcCore::app()->tpl->addValue('PingIfEven', FrontendTemplate::PingIfEven(...));
        dcCore::app()->tpl->addValue('BlogShortname', FrontendTemplate::BlogShortname(...));
        dcCore::app()->tpl->addValue('TagLabel', FrontendTemplate::TagLabel(...));

        return true;
    }
}
