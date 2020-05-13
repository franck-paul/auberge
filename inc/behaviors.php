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
        $room         = aubergeData::getUserRoom($core, $rs->user_id);
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

}

// Public behaviours

class aubergePublicBehaviors
{
}
