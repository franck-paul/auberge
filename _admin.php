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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

/*
dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(__('Auberge'), 'plugin.php?p=auberge', urldecode(dcPage::getPF('auberge/icon.png')),
    preg_match('/plugin.php\?p=auberge(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_USAGE,
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]), dcCore::app()->blog->id));
*/
dcCore::app()->addBehaviors([
    //'adminDashboardFavoritesV2' => [aubergeAdminBehaviors::class, 'adminDashboardFavorites'],

    // Generic admin behavior callbacks
    'adminPageHTMLHead'        => [aubergeAdminBehaviors::class, 'adminPageHTMLHead'],

    'adminUserListHeaderV2'    => [aubergeAdminBehaviors::class, 'adminUserListHeader'],
    'adminUserListValueV2'     => [aubergeAdminBehaviors::class, 'adminUserListValue'],

    // Add behaviour callbacks for user form
    'adminUserForm'            => [aubergeAdminBehaviors::class, 'adminUserForm'],
    'adminBeforeUserCreate'    => [aubergeAdminBehaviors::class, 'adminBeforeUserUpdate'],
    'adminBeforeUserUpdate'    => [aubergeAdminBehaviors::class, 'adminBeforeUserUpdate'],

    // Add behaviour callbacks for post form
    'adminPostFormItems'       => [aubergeAdminBehaviors::class, 'adminPostFormItems'],

    // Dashboard behaviours
    'adminDashboardContentsV2' => [aubergeAdminBehaviors::class, 'adminDashboardContents'],
]);

// Add behaviour callbacks for user lists
if (version_compare(DC_VERSION, '2.17-dev', '>=')) {
    dcCore::app()->addBehaviors([
        'adminGetUsers'         => [aubergeAdminBehaviors::class, 'adminGetUsers'],
        'adminUsersSortbyCombo' => [aubergeAdminBehaviors::class, 'adminUsersSortbyCombo'],
    ]);
}
