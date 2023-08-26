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

class Backend extends Process
{
    public static function init(): bool
    {
        // dead but useful code, in order to have translations
        __('auberge') . __('auberge');

        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        dcCore::app()->addBehaviors([
            // Generic admin behavior callbacks
            'adminPageHTMLHead' => BackendBehaviors::adminPageHTMLHead(...),

            'adminUserListHeaderV2' => BackendBehaviors::adminUserListHeader(...),
            'adminUserListValueV2'  => BackendBehaviors::adminUserListValue(...),

            // Add behaviour callbacks for user form
            'adminUserForm'         => BackendBehaviors::adminUserForm(...),
            'adminBeforeUserCreate' => BackendBehaviors::adminBeforeUserUpdate(...),
            'adminBeforeUserUpdate' => BackendBehaviors::adminBeforeUserUpdate(...),

            // Add behaviour callbacks for post form
            'adminPostFormItems' => BackendBehaviors::adminPostFormItems(...),

            // Dashboard behaviours
            'adminDashboardContentsV2' => BackendBehaviors::adminDashboardContents(...),

            // Add behaviour callbacks for user lists
            'adminGetUsers'         => BackendBehaviors::adminGetUsers(...),
            'adminUsersSortbyCombo' => BackendBehaviors::adminUsersSortbyCombo(...),
        ]);

        return true;
    }
}
