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

class aubergeData
{
    /**
     * Gets the user room.
     *
     * @param      <type>  $core     The core
     * @param      <type>  $user_id  The user identifier
     *
     * @return     <type>  The user room.
     */
    public static function getUserRoom($core, $user_id)
    {
        $sql =
        'SELECT U.room_id ' .
        'FROM ' . $core->prefix . 'user U ' .
        "WHERE U.user_id = '" . $core->con->escape($user_id) . "' ";

        $rs = $core->con->select($sql);

        return $rs->room_id;
    }

    /**
     * Gets the user staff role.
     *
     * @param      <type>  $core     The core
     * @param      <type>  $user_id  The user identifier
     *
     * @return     <type>  The user staff role.
     */
    public static function getUserStaffRole($core, $user_id)
    {
        $sql =
        'SELECT U.staff_role ' .
        'FROM ' . $core->prefix . 'user U ' .
        "WHERE U.user_id = '" . $core->con->escape($user_id) . "' ";

        $rs = $core->con->select($sql);

        return $rs->staff_role;
    }
}
