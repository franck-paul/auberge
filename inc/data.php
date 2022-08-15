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
    private static function getStay($core, $user_id, $dt)
    {
        if ($dt === null) {
            return false;
        }

        $stays = self::getUserStays(dcCore::app(), $user_id);
        if (is_array($stays) && count($stays)) {
            foreach ($stays as $stay) {
                $dt = date('Y-m-d H:i:00', strtotime($dt));
                // Limit of stay is the day after check_out + 11 hours
                $limit = date('Y-m-d H:i:00', strtotime($stay['check_out']) + (35 * 60 * 60));
                if ($dt <= $limit) {
                    return $stay;
                }
            }
            // Out of last stay, return it
            return $stays[count($stays) - 1];
        }

        return false;
    }

    /**
     * Gets the user room.
     *
     * @param      <type>  $core     The core
     * @param      <type>  $user_id  The user identifier
     *
     * @return     <type>  The user room.
     */
    public static function getUserRoom($core, $user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay(dcCore::app(), $user_id, $dt);
            if ($stay !== false) {
                return $stay['room_id'];
            }
        }

        $sql = 'SELECT U.room_id ' .
        'FROM ' . dcCore::app()->prefix . 'user U ' .
        "WHERE U.user_id = '" . dcCore::app()->con->escape($user_id) . "' ";

        $rs = dcCore::app()->con->select($sql);

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
    public static function getUserStaffRole($core, $user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay(dcCore::app(), $user_id, $dt);
            if ($stay !== false) {
                return aubergeUtils::getIdPosition($user_id, $stay['position']);
            }
        }

        $sql = 'SELECT U.staff_role ' .
        'FROM ' . dcCore::app()->prefix . 'user U ' .
        "WHERE U.user_id = '" . dcCore::app()->con->escape($user_id) . "' ";

        $rs = dcCore::app()->con->select($sql);

        return aubergeUtils::getIdPosition($user_id, $rs->staff_role);
    }

    /**
     * Gets the user check-in date.
     *
     * @param      <type>  $core     The core
     * @param      <type>  $user_id  The user identifier
     *
     * @return     <type>  The user check-in date.
     */
    public static function getUserCheckIn($core, $user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay(dcCore::app(), $user_id, $dt);
            if ($stay !== false) {
                return $stay['check_in'];
            }
        }

        $sql = 'SELECT U.check_in ' .
        'FROM ' . dcCore::app()->prefix . 'user U ' .
        "WHERE U.user_id = '" . dcCore::app()->con->escape($user_id) . "' ";

        $rs = dcCore::app()->con->select($sql);

        return $rs->check_in;
    }

    /**
     * Gets the user check-out date.
     *
     * @param      <type>  $core     The core
     * @param      <type>  $user_id  The user identifier
     *
     * @return     <type>  The user check-out date.
     */
    public static function getUserCheckOut($core, $user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay(dcCore::app(), $user_id, $dt);
            if ($stay !== false) {
                return $stay['check_out'];
            }
        }

        $sql = 'SELECT U.check_out ' .
        'FROM ' . dcCore::app()->prefix . 'user U ' .
        "WHERE U.user_id = '" . dcCore::app()->con->escape($user_id) . "' ";

        $rs = dcCore::app()->con->select($sql);

        return $rs->check_out;
    }

    /**
     * Gets the user stays (JSON encoded in db).
     *
     * @param      <type>  $core     The core
     * @param      <type>  $user_id  The user identifier
     *
     * @return     array  The user stays.
     */
    public static function getUserStays($core, $user_id)
    {
        $sql = 'SELECT U.stays ' .
        'FROM ' . dcCore::app()->prefix . 'user U ' .
        "WHERE U.user_id = '" . dcCore::app()->con->escape($user_id) . "' ";

        $rs = dcCore::app()->con->select($sql);
        if ($rs->stays) {
            $list = json_decode($rs->stays, true);
            usort($list, fn ($a, $b) => ($a['check_out'] > $b['check_out']));

            return $list;
        }

        return $rs->stays;
    }
}
