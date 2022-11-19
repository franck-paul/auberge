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
dcCore::app()->addBehavior('coreBlogGetComments', [aubergePublicBehaviors::class, 'coreBlogGetComments']);

// Public template tags
dcCore::app()->tpl->addValue('AuthorRoom', [aubergeTpl::class, 'authorRoom']);
dcCore::app()->tpl->addValue('AuthorRoomClass', [aubergeTpl::class, 'authorRoomClass']);
dcCore::app()->tpl->addValue('AuthorCheckIn', [aubergeTpl::class, 'authorCheckIn']);
dcCore::app()->tpl->addValue('AuthorCheckOut', [aubergeTpl::class, 'AuthorCheckOut']);
dcCore::app()->tpl->addValue('AuthorCheckStays', [aubergeTpl::class, 'AuthorCheckStays']);
dcCore::app()->tpl->addValue('CommentIfEven', [aubergeTpl::class, 'CommentIfEven']);
dcCore::app()->tpl->addValue('PingIfEven', [aubergeTpl::class, 'PingIfEven']);
dcCore::app()->tpl->addValue('BlogShortname', [aubergeTpl::class, 'BlogShortname']);
dcCore::app()->tpl->addValue('TagLabel', [aubergeTpl::class, 'TagLabel']);

if (version_compare(DC_VERSION, '2.17-dev', '<')) {
    // Add public template which are not yet in Dotclear (will be in next 2.17)
    dcCore::app()->tpl->addValue('BlogNbEntriesFirstPage', [aubergeTpl::class, 'BlogNbEntriesFirstPage']);
    dcCore::app()->tpl->addValue('BlogNbEntriesPerPage', [aubergeTpl::class, 'BlogNbEntriesPerPage']);
}

// Public template tags attribute
dcCore::app()->addBehavior('publicBeforeContentFilterV2', [aubergeTpl::class, 'publicBeforeContentFilter']);

// Add URL handler for archives
dcCore::app()->url->register('archive', 'archive', '^archive(/.+)?$', ['aubergeUrlHandlers', 'archive']);
