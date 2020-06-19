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

// Public behaviours
$core->addBehavior('coreBlogGetComments', ['aubergePublicBehaviors', 'coreBlogGetComments']);

// Public template tags
$core->tpl->addValue('AuthorRoom', ['aubergeTpl', 'authorRoom']);
$core->tpl->addValue('AuthorRoomClass', ['aubergeTpl', 'authorRoomClass']);
$core->tpl->addValue('AuthorCheckIn', ['aubergeTpl', 'authorCheckIn']);
$core->tpl->addValue('AuthorCheckOut', ['aubergeTpl', 'AuthorCheckOut']);
$core->tpl->addValue('AuthorCheckStays', ['aubergeTpl', 'AuthorCheckStays']);
$core->tpl->addValue('CommentIfEven', ['aubergeTpl', 'CommentIfEven']);
$core->tpl->addValue('PingIfEven', ['aubergeTpl', 'PingIfEven']);
$core->tpl->addValue('BlogShortname', ['aubergeTpl', 'BlogShortname']);

if (version_compare(DC_VERSION, '2.17-dev', '<')) {
    // Add public template which are not yet in Dotclear (will be in next 2.17)
    $core->tpl->addValue('BlogNbEntriesFirstPage', ['aubergeTpl', 'BlogNbEntriesFirstPage']);
    $core->tpl->addValue('BlogNbEntriesPerPage', ['aubergeTpl', 'BlogNbEntriesPerPage']);
}

// Add URL handler for archives
$core->url->register('archive', 'archive', '^archive(/.+)?$', ['aubergeUrlHandlers', 'archive']);
