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

class Backend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::checkContext(My::BACKEND);

        // dead but useful code, in order to have translations
        __('auberge') . __('auberge');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->addBehaviors([
            // Generic admin behavior callbacks
            'adminPageHTMLHead' => [BackendBehaviors::class, 'adminPageHTMLHead'],

            'adminUserListHeaderV2' => [BackendBehaviors::class, 'adminUserListHeader'],
            'adminUserListValueV2'  => [BackendBehaviors::class, 'adminUserListValue'],

            // Add behaviour callbacks for user form
            'adminUserForm'         => [BackendBehaviors::class, 'adminUserForm'],
            'adminBeforeUserCreate' => [BackendBehaviors::class, 'adminBeforeUserUpdate'],
            'adminBeforeUserUpdate' => [BackendBehaviors::class, 'adminBeforeUserUpdate'],

            // Add behaviour callbacks for post form
            'adminPostFormItems' => [BackendBehaviors::class, 'adminPostFormItems'],

            // Dashboard behaviours
            'adminDashboardContentsV2' => [BackendBehaviors::class, 'adminDashboardContents'],

            // Add behaviour callbacks for user lists
            'adminGetUsers'         => [BackendBehaviors::class, 'adminGetUsers'],
            'adminUsersSortbyCombo' => [BackendBehaviors::class, 'adminUsersSortbyCombo'],
        ]);

        return true;
    }
}
