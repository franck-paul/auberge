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
$_menu['Blog']->addItem(__('Auberge'), 'plugin.php?p=auberge', urldecode(dcPage::getPF('auberge/icon.png')),
    preg_match('/plugin.php\?p=auberge(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check('usage,contentadmin', dcCore::app()->blog->id));

dcCore::app()->addBehavior('adminDashboardFavorites', ['aubergeAdminBehaviors', 'adminDashboardFavorites']);
*/

// Generic admin behavior callbacks
dcCore::app()->addBehavior('adminPageHTMLHead', ['aubergeAdminBehaviors', 'adminPageHTMLHead']);

// Add behaviour callbacks for user lists
if (version_compare(DC_VERSION, '2.17-dev', '>=')) {
    dcCore::app()->addBehavior('adminGetUsers', ['aubergeAdminBehaviors', 'adminGetUsers']);
    dcCore::app()->addBehavior('adminUsersSortbyCombo', ['aubergeAdminBehaviors', 'adminUsersSortbyCombo']);
}
dcCore::app()->addBehavior('adminUserListHeader', ['aubergeAdminBehaviors', 'adminUserListHeader']);
dcCore::app()->addBehavior('adminUserListValue', ['aubergeAdminBehaviors', 'adminUserListValue']);

// Add behaviour callbacks for user form
dcCore::app()->addBehavior('adminUserForm', ['aubergeAdminBehaviors', 'adminUserForm']);
dcCore::app()->addBehavior('adminBeforeUserCreate', ['aubergeAdminBehaviors', 'adminBeforeUserUpdate']);
dcCore::app()->addBehavior('adminBeforeUserUpdate', ['aubergeAdminBehaviors', 'adminBeforeUserUpdate']);

// Add behaviour callbacks for post form
dcCore::app()->addBehavior('adminPostFormItems', ['aubergeAdminBehaviors', 'adminPostFormItems']);

// Dashboard behaviours
dcCore::app()->addBehavior('adminDashboardContents', ['aubergeAdminBehaviors', 'adminDashboardContents']);
