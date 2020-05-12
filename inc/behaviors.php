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
    public static function adminUserListHeader($core, $rs, $cols)
    {
        $cols['room'] = '<th scope="col" class="nowrap">' . __('Room') . '</th>';
    }

    public static function adminUserListValue($core, $rs, $cols)
    {
        $room = aubergeData::getUserRoom($core, $rs->user_id);
        $cols['room'] = '<td class="nowrap count">' . ($room ?: '' ) . '</td>';
    }

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

    public static function adminBeforeUserUpdate($cur, $user_id = '')
    {
        $cur->room_id = $_POST['user_room_id'];
    }

}

// Public behaviours

class aubergePublicBehaviors
{
}
