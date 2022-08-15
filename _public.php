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

// Public behaviours
dcCore::app()->addBehavior('coreBlogGetComments', ['aubergePublicBehaviors', 'coreBlogGetComments']);

// Public template tags
dcCore::app()->tpl->addValue('AuthorRoom', ['aubergeTpl', 'authorRoom']);
dcCore::app()->tpl->addValue('AuthorRoomClass', ['aubergeTpl', 'authorRoomClass']);
dcCore::app()->tpl->addValue('AuthorCheckIn', ['aubergeTpl', 'authorCheckIn']);
dcCore::app()->tpl->addValue('AuthorCheckOut', ['aubergeTpl', 'AuthorCheckOut']);
dcCore::app()->tpl->addValue('AuthorCheckStays', ['aubergeTpl', 'AuthorCheckStays']);
dcCore::app()->tpl->addValue('CommentIfEven', ['aubergeTpl', 'CommentIfEven']);
dcCore::app()->tpl->addValue('PingIfEven', ['aubergeTpl', 'PingIfEven']);
dcCore::app()->tpl->addValue('BlogShortname', ['aubergeTpl', 'BlogShortname']);
dcCore::app()->tpl->addValue('TagLabel', ['aubergeTpl', 'TagLabel']);

if (version_compare(DC_VERSION, '2.17-dev', '<')) {
    // Add public template which are not yet in Dotclear (will be in next 2.17)
    dcCore::app()->tpl->addValue('BlogNbEntriesFirstPage', ['aubergeTpl', 'BlogNbEntriesFirstPage']);
    dcCore::app()->tpl->addValue('BlogNbEntriesPerPage', ['aubergeTpl', 'BlogNbEntriesPerPage']);
}

// Public template tags attribute
dcCore::app()->addBehavior('publicBeforeContentFilter', ['aubergeTpl', 'publicBeforeContentFilter']);

// Add URL handler for archives
dcCore::app()->url->register('archive', 'archive', '^archive(/.+)?$', ['aubergeUrlHandlers', 'archive']);
