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

dcCore::app()->addBehavior('adminDashboardFavoritesV2', [aubergeAdminBehaviors::class, 'adminDashboardFavorites']);
*/

// Generic admin behavior callbacks
dcCore::app()->addBehavior('adminPageHTMLHead', [aubergeAdminBehaviors::class, 'adminPageHTMLHead']);

// Add behaviour callbacks for user lists
if (version_compare(DC_VERSION, '2.17-dev', '>=')) {
    dcCore::app()->addBehavior('adminGetUsers', [aubergeAdminBehaviors::class, 'adminGetUsers']);
    dcCore::app()->addBehavior('adminUsersSortbyCombo', [aubergeAdminBehaviors::class, 'adminUsersSortbyCombo']);
}
dcCore::app()->addBehavior('adminUserListHeaderV2', [aubergeAdminBehaviors::class, 'adminUserListHeader']);
dcCore::app()->addBehavior('adminUserListValueV2', [aubergeAdminBehaviors::class, 'adminUserListValue']);

// Add behaviour callbacks for user form
dcCore::app()->addBehavior('adminUserForm', [aubergeAdminBehaviors::class, 'adminUserForm']);
dcCore::app()->addBehavior('adminBeforeUserCreate', [aubergeAdminBehaviors::class, 'adminBeforeUserUpdate']);
dcCore::app()->addBehavior('adminBeforeUserUpdate', [aubergeAdminBehaviors::class, 'adminBeforeUserUpdate']);

// Add behaviour callbacks for post form
dcCore::app()->addBehavior('adminPostFormItems', [aubergeAdminBehaviors::class, 'adminPostFormItems']);

// Dashboard behaviours
dcCore::app()->addBehavior('adminDashboardContentsV2', [aubergeAdminBehaviors::class, 'adminDashboardContents']);
