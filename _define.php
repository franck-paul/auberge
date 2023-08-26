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
$this->registerModule(
    'Auberge des blogueurs',
    'Des vis et des boulons',
    'Franck Paul',
    '2.0',
    [
        'requires'    => [['core', '2.27'], ['php', '8.1']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_USAGE,
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]),
        'type'     => 'plugin',
        'priority' => 1500,
        'settings' => [
        ],

        'details'    => 'https://open-time.net/?q=auberge',
        'support'    => 'https://github.com/franck-paul/auberge',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/auberge/master/dcstore.xml',
    ]
);
