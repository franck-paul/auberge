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
    public static function adminPageHTMLHead()
    {
        global $core;

        // Ajout feuille de style spécifique
        echo
        dcPage::cssLoad(urldecode(dcPage::getPF('auberge/css/admin.css')), 'screen', $core->getVersion('auberge'));

        // Ajout favicon spécifique
        if ($core->auth->user_prefs->interface->hide_std_favicon) {
            echo
                '<link rel="icon" type="image/png" href="favicon.png" />' . "\n" .
                '<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />' . "\n";
        }
    }

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
                $params['columns'][] = 'staff_role';
                $params['columns'][] = 'check_in';
                $params['columns'][] = 'check_out';
            } else {
                $params['columns'] .= ',room_id,staff_role,check_in,check_out';
            }
        } else {
            $params['columns'] = ['room_id', 'staff_role', 'check_in', 'check_out'];
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
        $opt[0][__('Check-in')] = 'check_in';
        $opt[0][__('Check-out')] = 'check_out';
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
        $cols['room']      = '<th scope="col" class="nowrap">' . __('Room') . '</th>';
        $cols['role']      = '<th scope="col" class="nowrap">' . __('Staff role') . '</th>';
        $cols['check_in']  = '<th scope="col" class="nowrap">' . __('Check-in') . '</th>';
        $cols['check_out'] = '<th scope="col" class="nowrap">' . __('Check-out') . '</th>';
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
            $room      = $rs->room_id;
            $role      = $rs->staff_role;
            $check_in  = $rs->check_in;
            $check_out = $rs->check_out;
        } else {
            $room      = aubergeData::getUserRoom($core, $rs->user_id);
            $role      = aubergeData::getUserStaffRole($core, $rs->user_id);
            $check_in  = aubergeData::getUserCheckIn($core, $rs->user_id);
            $check_out = aubergeData::getUserCheckOut($core, $rs->user_id);
        }
        $cols['room']      = '<td class="nowrap count">' . ($room ?: '') . '</td>';
        $cols['role']      = '<td class="nowrap">' . ($role ?: '') . '</td>';
        $cols['check_in']  = '<td class="nowrap">' . (strtotime($check_in) > 0 ? dt::dt2str(__('%Y-%m-%d'), $check_in) : '') . '</td>';
        $cols['check_out'] = '<td class="nowrap">' . (strtotime($check_out) > 0 ? dt::dt2str(__('%Y-%m-%d'), $check_out) : '') . '</td>';
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
            $room      = aubergeData::getUserRoom($core, $rs->user_id);
            $role      = aubergeData::getUserStaffRole($core, $rs->user_id);
            $check_in  = aubergeData::getUserCheckIn($core, $rs->user_id);
            $check_out = aubergeData::getUserCheckOut($core, $rs->user_id);
        } else {
            $room      = 0;
            $role      = '';
            $check_in  = 0;
            $check_out = 0;
        }

        echo
        '<div class="fieldset"><h5 id="auberge">' .
        '<img src="' . urldecode(dcPage::getPF('auberge/icon.png')) . '" alt="" />' . ' ' . __('Auberge') . '</h5>' .
        '<p class="field"><label for="user_room_id">' . __('Room number:') . '</label> ' .
        form::number('user_room_id', [
            'min'     => 0,
            'max'     => 1999,
            'default' => $room
        ]) .
        '</p>' .
        '<p class="form-note">' . __('0 = not set, 1 to 999 = residents, 1000+ = staff.') . '</p>' .
        '<p class="field"><label for="user_staff_role">' . __('Staff role:') . '</label> ' .
        form::field('user_staff_role', 20, 255, [
            'default'      => html::escapeHTML($role),
            'autocomplete' => __('staff role')
        ]) .
        '</p>' .
        '<p class="form-note">' . __('Used only for staff member.') . '</p>';

        echo '<hr /><p>';
        if (strtotime($check_in) <= 0 || strtotime($check_out) <= 0) {
            echo __('Dates of stay are not yet known.');
        } else {
            echo sprintf(__('Stay in the hostel from <strong>%s</strong> to <strong>%s</strong>'),
                dt::dt2str(__('%A, %B %e %Y'), $check_in),
                dt::dt2str(__('%A, %B %e %Y'), $check_out));
        }
        echo '</p></div>';
    }

    /**
     * Cope with user being created or updated
     *
     * @param      cursor  $cur      User cursor
     * @param      string  $user_id  The user identifier
     */
    public static function adminBeforeUserUpdate($cur, $user_id = '')
    {
        $cur->room_id    = (integer) $_POST['user_room_id'];
        $cur->staff_role = $_POST['user_staff_role'];
    }

    public static function adminDashboardContents($core, $contents)
    {
        // Add modules to the contents stack
        $forum_url = defined('DC_AUBERGE_FORUM_URL') ? DC_AUBERGE_FORUM_URL : '#';
        if ($core->auth->isSuperAdmin() || ($core->blog && $core->auth->check('contentadmin', $core->blog->id))) {
            $contact_url = $core->url->getURLFor('contactme');
        } else {
            // URL is not available in dashboard for non-admin
            $contact_url = $core->blog->getQmarkURL() . 'contact';
        }

        // Get user info
        $room_id    = aubergeData::getUserRoom($core, $core->auth->userID());
        $staff_role = aubergeData::getUserStaffRole($core, $core->auth->userID());
        $is_staff   = false;
        if ($room_id > 0 && $room_id < 1000) {
            // Single resident
        } elseif ($room_id >= 1000) {
            $is_staff = true;
            $room_id -= 999;
        }

        // Compose module content
        $ret = '<div id="auberge" class="box badgeable">';

        $title = __('Welcome to the hostel %s!');

        // Title: Pseudo
        $ret .= '<h3>' . '<img src="' . urldecode(dcPage::getPF('auberge/icon.png')) . '" alt="" />' . ' ' .
        sprintf($title, $core->auth->getInfo('user_displayname')) . '</h3>';

        // Room number
        if ($room_id > 0) {
            $info = $is_staff ? __('You\'re staying in the <strong>staff</strong> room number') : __('You\'re staying in room number');
            $ret .= '<p>' . $info . '<strong class="badge badge-inline' . ($is_staff ? ' badge-info' : '') . '">' .
            sprintf('%d', $room_id) . '</strong><br />';
            if ($staff_role) {
                $ret .= sprintf(__('Your staff position is: <strong>%s</strong>'), $staff_role) . '<br />';
            }
        } else {
            $ret .= '<p>' . __('No room assigned yet.') . '<br />';
        }
        // Stay dates
        $check_in  = aubergeData::getUserCheckIn($core, $core->auth->userID());
        $check_out = aubergeData::getUserCheckOut($core, $core->auth->userID());
        if (strtotime($check_in) <= 0 || strtotime($check_out) <= 0) {
            $ret .= __('Your dates of stay are not yet known.');
        } else {
            $ret .= sprintf(__('You stay in the hostel from <strong>%s</strong> to <strong>%s</strong>'),
                dt::dt2str(__('%A, %B %e %Y'), $check_in),
                dt::dt2str(__('%A, %B %e %Y'), $check_out));
        }
        $ret .= '</p>';

        // User pseudo and email
        $info = sprintf(
            __('The email you use for this game is: <strong>%s</strong> (it will be known only to innkeepers).'),
            $core->auth->getInfo('user_email')
        );
        $ret .= '<p>' . $info . '</p>';

        // Contact/Forum infos
        $ret .= '<hr />' .
        '<p>' .
        sprintf(__('<a href="%s">Contact the organizers of the game</a>'), $contact_url) . '<br />' .
        sprintf(__('<a href="%s">Forum</a>'), $forum_url) .
            '</p>';

        $ret .= '</div>';
        $contents[] = new ArrayObject([$ret]);

        // Other actions on Dashboard
        if (!$core->auth->isSuperAdmin() && !$core->blog && $core->auth->check('contentadmin', $core->blog->id)) {
            // Remove uick entry from Dashboard
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
