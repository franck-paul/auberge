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

$this->registerModule(
    "Auberge des blogueurs",  // Name
    "Des vis et des boulons", // Description
    "Franck Paul",            // Author
    '0.16.2',                 // Version
    [
        'requires'    => [['core', '2.16']],   // Requirements
        'permissions' => 'usage,contentadmin', // Permissions
        'type'        => 'plugin',             // Type
        'priority'    => 1500,                 // Priority
        'settings'    => [                     // Settings
        ]
    ]
);
