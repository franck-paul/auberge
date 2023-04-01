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

use Dotclear\Helper\Clearbricks;

// Public and Admin mode

Clearbricks::lib()->autoload([
    'aubergePublicBehaviors'    => __DIR__ . '/inc/behaviors.php',
    'aubergeUrlHandlers'        => __DIR__ . '/inc/behaviors.php',
    'rsAubergeExtCommentPublic' => __DIR__ . '/inc/behaviors.php',
    'aubergeTpl'                => __DIR__ . '/inc/tpl.php',
    'aubergeData'               => __DIR__ . '/inc/data.php',
    'aubergeUtils'              => __DIR__ . '/inc/utils.php',
]);

// Add URL handler for archives
dcCore::app()->url->register('archive', 'archive', '^archive(/.+)?$', ['aubergeUrlHandlers', 'archive']);

if (!defined('DC_CONTEXT_ADMIN')) {
    return false;
}

// Admin mode only

Clearbricks::lib()->autoload(['aubergeAdminBehaviors' => __DIR__ . '/inc/behaviors.php']);
