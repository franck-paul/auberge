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

use Dotclear\App;
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
        App::behavior()->addBehaviors([
            'coreBlogGetComments' => FrontendBehaviors::coreBlogGetComments(...),

            'publicBeforeContentFilterV2' => FrontendBehaviors::publicBeforeContentFilter(...),
        ]);

        // Public template tags
        App::frontend()->template()->addValue('AuthorRoom', FrontendTemplate::authorRoom(...));
        App::frontend()->template()->addValue('AuthorRoomClass', FrontendTemplate::authorRoomClass(...));
        App::frontend()->template()->addValue('AuthorCheckIn', FrontendTemplate::authorCheckIn(...));
        App::frontend()->template()->addValue('AuthorCheckOut', FrontendTemplate::AuthorCheckOut(...));
        App::frontend()->template()->addValue('AuthorCheckStays', FrontendTemplate::AuthorCheckStays(...));
        App::frontend()->template()->addValue('CommentIfEven', FrontendTemplate::CommentIfEven(...));
        App::frontend()->template()->addValue('PingIfEven', FrontendTemplate::PingIfEven(...));
        App::frontend()->template()->addValue('BlogShortname', FrontendTemplate::BlogShortname(...));
        App::frontend()->template()->addValue('TagLabel', FrontendTemplate::TagLabel(...));

        return true;
    }
}
