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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

/*
$_menu['Blog']->addItem(__('Auberge'), 'plugin.php?p=auberge', urldecode(dcPage::getPF('auberge/icon.png')),
    preg_match('/plugin.php\?p=auberge(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->check('usage,contentadmin', $core->blog->id));

$core->addBehavior('adminDashboardIcons', ['aubergeAdminBehaviors', 'adminDashboardIcons']);
$core->addBehavior('adminDashboardFavorites', ['aubergeAdminBehaviors', 'adminDashboardFavorites']);
*/

// Add behaviour callbacks for user lists
if (version_compare(DC_VERSION, '2.17-dev', '>=')) {
    $core->addBehavior('adminGetUsers', ['aubergeAdminBehaviors', 'adminGetUsers']);
    $core->addBehavior('adminUsersSortbyCombo', ['aubergeAdminBehaviors', 'adminUsersSortbyCombo']);
}
$core->addBehavior('adminUserListHeader', ['aubergeAdminBehaviors', 'adminUserListHeader']);
$core->addBehavior('adminUserListValue', ['aubergeAdminBehaviors', 'adminUserListValue']);

// Add behaviour callbacks for user form
$core->addBehavior('adminUserForm', ['aubergeAdminBehaviors', 'adminUserForm']);
$core->addBehavior('adminBeforeUserCreate', ['aubergeAdminBehaviors', 'adminBeforeUserUpdate']);
$core->addBehavior('adminBeforeUserUpdate', ['aubergeAdminBehaviors', 'adminBeforeUserUpdate']);

// Add behaviour callbacks for post form
$core->addBehavior('adminPostFormItems', ['aubergeAdminBehaviors', 'adminPostFormItems']);

// Dashboard behaviours
$core->addBehavior('adminDashboardContents', ['aubergeAdminBehaviors', 'adminDashboardContents']);
