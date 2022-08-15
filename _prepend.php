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
if (!defined('DC_RC_PATH')) {
    return;
}

// Public and Admin mode

$__autoload['aubergePublicBehaviors']    = __DIR__ . '/inc/behaviors.php';
$__autoload['aubergeUrlHandlers']        = __DIR__ . '/inc/behaviors.php';
$__autoload['rsAubergeExtCommentPublic'] = __DIR__ . '/inc/behaviors.php';
$__autoload['aubergeTpl']                = __DIR__ . '/inc/tpl.php';
$__autoload['aubergeData']               = __DIR__ . '/inc/data.php';
$__autoload['aubergeUtils']              = __DIR__ . '/inc/utils.php';

if (!defined('DC_CONTEXT_ADMIN')) {
    return false;
}

// Admin mode only

$__autoload['aubergeAdminBehaviors'] = __DIR__ . '/inc/behaviors.php';
