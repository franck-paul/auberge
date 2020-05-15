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

// Admin behaviours

class aubergeAdminBehaviors
{
    public static function adminPostFormItems($main, $sidebar, $post)
    {
        global $core;

        if ($core->auth->isSuperAdmin() || ($core->blog && $core->auth->check('contentadmin', $core->blog->id))) {
            // No change for super-admin or blog's admins
            return;
        }

        // Main items: no change

        // Sidebar items:

        // - status-box: no change

        // - metas-box: remove all except fields which be replaced by hidden fields
        $sidebar['metas-box']['title']                  = '';
        $sidebar['metas-box']['items']['post_selected'] = form::hidden('post_selected', $post ? $post->post_selected : false);
        $sidebar['metas-box']['items']['cat_id']        = form::hidden('cat_id', $post ? $post->cat_id : null);

        $sidebar['metas-box']['items']['auberge'] = '<style type="text/css">#metas-box {display: none}</style>';

        // - options-box remove all except fields which be replaced by hidden fields
        $sidebar['options-box']['title']                         = '';
        $sidebar['options-box']['items']['post_open_comment_tb'] =
        form::hidden('post_open_comment', $post ? $post->post_open_comment : true) .
        form::hidden('post_open_tb', $post ? $post->post_open_tb : true);
        $sidebar['options-box']['items']['post_password'] = form::hidden('post_password', $post ? $post->post_password : null);
        $sidebar['options-box']['items']['post_url']      = form::hidden('post_url', $post ? $post->post_url : null);

        $sidebar['options-box']['items']['auberge'] = '<style type="text/css">#options-box {display: none}</style>';
    }

    /**
     * Add columns when get users list in admin
     *
     * @param      array  $params  The parameters
     */
    public static function adminGetUsers($params)
    {
        if (!empty($params['columns'])) {
            if (is_array($params['columns'])) {
                $params['columns'][] = 'room_id';
            } else {
                $params['columns'] .= ',room_id';
            }
        } else {
            $params['columns'] = ['room_id'];
        }
    }

    /**
     * Add room to "sort by" combo (used in admin users lists)
     *
     * @param      <type>  $opt    The option
     */
    public static function adminUsersSortbyCombo($opt)
    {
        $opt[0][__('Room')] = 'room_id';
    }

    /**
     * Add room column header in user's list
     *
     * @param               $core   The core
     * @param      record   $rs     users records
     * @param      array    $cols   The cols
     */
    public static function adminUserListHeader($core, $rs, $cols)
    {
        $cols['room'] = '<th scope="col" class="nowrap">' . __('Room') . '</th>';
    }

    /**
     * Add room number cell in user's list
     *
     * @param              $core   The core
     * @param      record  $rs     current user record
     * @param      array   $cols   The cols
     */
    public static function adminUserListValue($core, $rs, $cols)
    {
        if (version_compare(DC_VERSION, '2.17-dev', '>=')) {
            $room = $rs->room_id;
        } else {
            $room = aubergeData::getUserRoom($core, $rs->user_id);
        }
        $cols['room'] = '<td class="nowrap count">' . ($room ?: '') . '</td>';
    }

    /**
     * Add room number input field in user form
     *
     * @param      record  $rs     user record
     */
    public static function adminUserForm($rs)
    {
        global $core;

        if ($rs instanceof record) {
            $room = aubergeData::getUserRoom($core, $rs->user_id);
        } else {
            $room = 0;
        }

        echo
        '<div class="fieldset"><h5 id="auberge">' . __('Auberge') . '</h5>' .
        '<p class="field"><label for="user_room_id">' . __('Room number:') . '</label> ' .
        form::number('user_room_id', [
            'min'     => 0,
            'max'     => 1999,
            'default' => $room
        ]) .
        '</p>' .
        '<p class="form-note">' . __('0 = not set, 1 to 999 = residents, 1000+ = staff.') . '</p>' .
            '</div>';
    }

    /**
     * Cope with user being created or updated
     *
     * @param      cursor  $cur      User cursor
     * @param      string  $user_id  The user identifier
     */
    public static function adminBeforeUserUpdate($cur, $user_id = '')
    {
        $cur->room_id = $_POST['user_room_id'];
    }

    public static function adminDashboardContents($core, $contents)
    {
        // Add modules to the contents stack
        $class = 'small';   // Small box is enough, up to now
        $ret   = '<div id="auberge" class="box ' . $class . '">' .
        '<h3>' . '<img src="' . urldecode(dcPage::getPF('auberge/icon.png')) . '" alt="" />' . ' ' . __('Auberge') . '</h3>';
        $room_id  = aubergeData::getUserRoom($core, $core->auth->userID());
        $is_staff = false;
        if ($room_id > 0 && $room_id < 1000) {
            // Single resident
        } elseif ($room_id >= 1000) {
            $is_staff = true;
            $room_id -= 999;
        }
        $info = $is_staff ? __('Staff room number:') : __('Room number:');
        if ($room_id > 0) {
            $ret .= '<p>' . $info . ' ' . sprintf('%d', $room_id) . '</p>';
        } else {
            $ret .= '<p>' . __('No room') . '</p>';
        }
        $ret .= '</div>';
        $contents[] = new ArrayObject([$ret]);

        if (!$core->auth->isSuperAdmin() && !$core->blog && $core->auth->check('contentadmin', $core->blog->id)) {
            // Remove Quick entry from Dashboard
            $core->auth->user_prefs->dashboard->put('quickentry', false, 'boolean');
        }
    }

    public function adminDashboardIcons($core, $icons)
    {
        $icons['auberge'] = new ArrayObject([
            __('auberge'),
            $core->adminurl->get('admin.plugin.auberge'),
            dcPage::getPF('auberge/icon-db.png')
        ]);
    }

    public function adminDashboardFavorites($core, $favs)
    {
        $favs->register('auberge', [
            'title'       => __('Auberge'),
            'url'         => $core->adminurl->get('admin.plugin.auberge'),
            'small-icon'  => dcPage::getPF('auberge/icon.png'),
            'large-icon'  => dcPage::getPF('auberge/icon-db.png'),
            'permissions' => 'contentadmin'
        ]);
    }
}

// Public behaviours

class aubergePublicBehaviors
{
    public static function coreBlogGetComments($rs)
    {
        $rs->extend('rsAubergeExtCommentPublic');
    }
}

class rsAubergeExtCommentPublic extends rsExtComment
{
    /**
    Returns whether comment is from the post author.

    @param    rs    Invisible parameter
    @return    <b>boolean</b>
     */
    public static function isMe($rs)
    {
        return
        $rs->comment_email &&
        $rs->comment_email == $rs->user_email;
    }
}

// URL handlers

class aubergeUrlHandlers extends dcUrlHandlers
{
    /**
     * URL Handler for single-page archive with redirect to page anchor if necessary
     *
     * @param      <type>  $args   The arguments
     */
    public static function archive($args)
    {
        $_ctx = &$GLOBALS['_ctx'];
        $core = &$GLOBALS['core'];

        $anchor = '';
        if (preg_match('|^/([0-9]{4})/([0-9]{2})$|', $args, $m)) {
            $anchor = '#Y' . $m[1] . '-M' . $m[2];
            http::redirect($core->blog->url . $core->url->getURLFor('archive') . $anchor);
        }
        self::serveDocument('archive.html');
    }
}
