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

use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Network\Http;

// Admin behaviours

class aubergeAdminBehaviors
{
    public static function adminPageHTMLHead()
    {
        // Ajout feuille de style spécifique
        echo
        dcPage::cssModuleLoad('auberge/css/admin.css', 'screen', dcCore::app()->getVersion('auberge'));

        if (dcCore::app()->auth->isSuperAdmin() || (dcCore::app()->blog && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]), dcCore::app()->blog->id))) {
        } else {
            // Ajout feuille de style spécifique non admin
            echo
            dcPage::cssModuleLoad('auberge/css/admin-usage.css', 'screen', dcCore::app()->getVersion('auberge'));
        }

        // Ajout favicon spécifique
        if (dcCore::app()->auth->user_prefs->interface->hide_std_favicon) {
            echo
                '<link rel="icon" type="image/png" href="favicon.png" />' . "\n" .
                '<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />' . "\n";
        }
    }

    public static function adminPostFormItems($main, $sidebar, $post)
    {
        if (dcCore::app()->auth->isSuperAdmin() || (dcCore::app()->blog && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]), dcCore::app()->blog->id))) {
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
        $sidebar['options-box']['items']['post_open_comment_tb'] = form::hidden('post_open_comment', $post ? $post->post_open_comment : true) .
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
        $opt[0][__('Room')]      = 'room_id';
        $opt[0][__('Check-in')]  = 'check_in';
        $opt[0][__('Check-out')] = 'check_out';
    }

    /**
     * Add room column header in user's list
     *
     * @param               $core   The core
     * @param      record   $rs     users records
     * @param      array    $cols   The cols
     */
    public static function adminUserListHeader($rs, $cols)
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
    public static function adminUserListValue($rs, $cols)
    {
        if (version_compare(DC_VERSION, '2.17-dev', '>=')) {
            $room      = $rs->room_id;
            $role      = $rs->staff_role;
            $check_in  = $rs->check_in;
            $check_out = $rs->check_out;
        } else {
            $room      = aubergeData::getUserRoom(dcCore::app(), $rs->user_id);
            $role      = aubergeData::getUserStaffRole(dcCore::app(), $rs->user_id);
            $check_in  = aubergeData::getUserCheckIn(dcCore::app(), $rs->user_id);
            $check_out = aubergeData::getUserCheckOut(dcCore::app(), $rs->user_id);
        }
        $cols['room']      = '<td class="nowrap count">' . ($room ?: '') . '</td>';
        $cols['role']      = '<td class="nowrap">' . ($role ?: '') . '</td>';
        $cols['check_in']  = '<td class="nowrap">' . (strtotime($check_in) > 0 ? dt::dt2str(__('%Y-%m-%d'), $check_in) : '') . '</td>';
        $cols['check_out'] = '<td class="nowrap">' . (strtotime($check_out) > 0 ? dt::dt2str(__('%Y-%m-%d'), $check_out) : '') . '</td>';
    }

    /**
     * Add room number input field in user form
     *
     * @param      null|record  $rs     user record
     */
    public static function adminUserForm($rs)
    {
        if ($rs) {
            $room      = aubergeData::getUserRoom(dcCore::app(), $rs->user_id);
            $role      = aubergeData::getUserStaffRole(dcCore::app(), $rs->user_id);
            $check_in  = aubergeData::getUserCheckIn(dcCore::app(), $rs->user_id);
            $check_out = aubergeData::getUserCheckOut(dcCore::app(), $rs->user_id);
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
            'default' => $room,
        ]) .
        '</p>' .
        '<p class="form-note">' . __('0 = not set, 1 to 999 = residents, 1000+ = staff.') . '</p>' .
        '<p class="field"><label for="user_staff_role">' . __('Staff role:') . '</label> ' .
        form::field('user_staff_role', 20, 255, [
            'default'      => Html::escapeHTML($role),
            'autocomplete' => __('staff role'),
        ]) .
        '</p>' .
        '<p class="form-note">' . __('Used only for staff member.') . '</p>';

        echo '<hr /><p>';
        if (strtotime($check_in) <= 0 || strtotime($check_out) <= 0) {
            echo __('Dates of stay are not yet known.');
        } else {
            echo sprintf(
                __('Stay in the hostel from <strong>%s</strong> to <strong>%s</strong>'),
                dt::dt2str(__('%A, %B %e %Y'), $check_in),
                dt::dt2str(__('%A, %B %e %Y'), $check_out)
            );
        }
        echo '</p></div>';
    }

    /**
     * Cope with user being created or updated
     *
     * @param      cursor  $cur      User cursor
     * @param      string  $user_id  The user identifier
     */
    public static function adminBeforeUserUpdate($cur)
    {
        $cur->room_id    = (int) $_POST['user_room_id'];
        $cur->staff_role = $_POST['user_staff_role'];
    }

    public static function adminDashboardContents($contents)
    {
        // Add modules to the contents stack
        $forum_url = defined('DC_AUBERGE_FORUM_URL') ? DC_AUBERGE_FORUM_URL : '#';
        if (dcCore::app()->auth->isSuperAdmin() || (dcCore::app()->blog && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]), dcCore::app()->blog->id))) {
            $contact_url = dcCore::app()->blog->url . dcCore::app()->url->getURLFor('contactme');
        } else {
            // URL is not available in dashboard for non-admin, so ugly code !!!
            $contact_url = dcCore::app()->blog->url . 'contact';
        }

        // Compose module content
        $ret = '<div id="auberge" class="box badgeable">';

        $title = __('Welcome to the hostel %s!');

        // Title: Pseudo
        $ret .= '<h3>' . '<img src="' . urldecode(dcPage::getPF('auberge/icon.png')) . '" alt="" />' . ' ' .
        sprintf($title, dcCore::app()->auth->getInfo('user_displayname')) . '</h3>';

        // Stays
        $stays = aubergeData::getUserStays(dcCore::app(), dcCore::app()->auth->userID());
        if ($stays) {
            foreach ($stays as $stay) {
                $check_in  = $stay['check_in'];
                $check_out = $stay['check_out'];
                $room_id   = $stay['room_id'];
                $position  = aubergeUtils::getIdPosition(dcCore::app()->auth->userID(), $stay['position']);

                $is_staff = false;
                if ($room_id > 0 && $room_id < 1000) {
                    // Single resident
                } elseif ($room_id >= 1000) {
                    $is_staff = true;
                    $room_id -= 999;
                }
                $ret .= '<p>';
                $ret .= sprintf(
                    __('You stay in the hostel from <strong>%s</strong> to <strong>%s</strong>'),
                    dt::dt2str(__('%A, %B %e %Y'), $check_in),
                    dt::dt2str(__('%A, %B %e %Y'), $check_out)
                );
                if ($room_id > 0) {
                    $info = $is_staff ? __('You\'re staying in the <strong>staff</strong> room number') : __('You\'re staying in room number');
                    $ret .= '<br />' . $info . '<strong class="badge badge-inline' . ($is_staff ? ' badge-info' : '') . '">' .
                    sprintf('%d', $room_id) . '</strong><br />';
                    if ($is_staff && $position) {
                        $ret .= sprintf(__('Your staff position is: <strong>%s</strong>'), $position);
                    }
                } else {
                    $ret .= __('No room assigned yet.');
                }
                $ret .= '</p>';
            }
        } else {
            $ret .= __('Your dates of stay are not yet known.');
        }

        $ret .= '</p>';

        // User pseudo and email
        $info = sprintf(
            __('The email you use for this game is: <strong>%s</strong> (it will be known only to innkeepers).'),
            dcCore::app()->auth->getInfo('user_email')
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
        if (!dcCore::app()->auth->isSuperAdmin() && !dcCore::app()->blog && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]), dcCore::app()->blog->id)) {
            // Remove uick entry from Dashboard
            dcCore::app()->auth->user_prefs->dashboard->put('quickentry', false, 'boolean');
        }
    }

    public function adminDashboardFavorites($favs)
    {
        $favs->register('auberge', [
            'title'       => __('Auberge'),
            'url'         => dcCore::app()->adminurl->get('admin.plugin.auberge'),
            'small-icon'  => dcPage::getPF('auberge/icon.png'),
            'large-icon'  => dcPage::getPF('auberge/icon-db.png'),
            'permissions' => dcCore::app()->auth->makePermissions([
                dcAuth::PERMISSION_CONTENT_ADMIN,
            ]),
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
        $rs->comment_email && $rs->comment_email == $rs->user_email;
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
        $anchor = '';
        if (preg_match('|^/(\d{4})/(\d{2})$|', $args, $m)) {
            $anchor = '#Y' . $m[1] . '-M' . $m[2];
            Http::redirect(dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive') . $anchor);
        }
        self::serveDocument('archive.html');
    }
}
