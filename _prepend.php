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

if (!defined('DC_RC_PATH')) {return;}

// Public and Admin mode

$__autoload['aubergePublicBehaviors'] = dirname(__FILE__) . '/inc/behaviors.php';
$__autoload['aubergeUrlHandlers']     = dirname(__FILE__) . '/inc/behaviors.php';
$__autoload['aubergeTpl']             = dirname(__FILE__) . '/inc/tpl.php';
$__autoload['aubergeData']            = dirname(__FILE__) . '/inc/data.php';

if (!defined('DC_CONTEXT_ADMIN')) {return false;}

// Admin mode only

$__autoload['aubergeAdminBehaviors'] = dirname(__FILE__) . '/inc/behaviors.php';
