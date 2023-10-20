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
declare(strict_types=1);

namespace Dotclear\Plugin\auberge;

use Dotclear\App;

class CoreData
{
    /**
     * Gets the user stay.
     *
     * @param      mixed   $user_id  The user identifier
     * @param      mixed   $dt       The date
     *
     * @return     mixed  The user stay (or false).
     */
    private static function getStay($user_id, $dt)
    {
        if ($dt === null) {
            return false;
        }

        $stays = self::getUserStays($user_id);
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
     * @param      mixed   $user_id  The user identifier
     * @param      mixed   $dt       The date
     *
     * @return     mixed  The user room.
     */
    public static function getUserRoom($user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay($user_id, $dt);
            if ($stay !== false) {
                return $stay['room_id'];
            }
        }

        $sql = 'SELECT U.room_id ' .
        'FROM ' . App::con()->prefix() . 'user U ' .
        "WHERE U.user_id = '" . App::con()->escape($user_id) . "' ";

        $rs = App::con()->select($sql);

        return $rs->room_id;
    }

    /**
     * Gets the user staff role.
     *
     * @param      mixed   $user_id  The user identifier
     * @param      mixed   $dt       The date
     *
     * @return     mixed  The user staff role.
     */
    public static function getUserStaffRole($user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay($user_id, $dt);
            if ($stay !== false) {
                return CoreHelper::getIdPosition($user_id, $stay['position']);
            }
        }

        $sql = 'SELECT U.staff_role ' .
        'FROM ' . App::con()->prefix() . 'user U ' .
        "WHERE U.user_id = '" . App::con()->escape($user_id) . "' ";

        $rs = App::con()->select($sql);

        return CoreHelper::getIdPosition($user_id, $rs->staff_role);
    }

    /**
     * Gets the user check-in date.
     *
     * @param      mixed   $user_id  The user identifier
     * @param      mixed   $dt       The date
     *
     * @return     mixed  The user check-in date.
     */
    public static function getUserCheckIn($user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay($user_id, $dt);
            if ($stay !== false) {
                return $stay['check_in'];
            }
        }

        $sql = 'SELECT U.check_in ' .
        'FROM ' . App::con()->prefix() . 'user U ' .
        "WHERE U.user_id = '" . App::con()->escape($user_id) . "' ";

        $rs = App::con()->select($sql);

        return $rs->check_in;
    }

    /**
     * Gets the user check-out date.
     *
     * @param      mixed   $user_id  The user identifier
     * @param      mixed   $dt       The date
     *
     * @return     mixed  The user check-out date.
     */
    public static function getUserCheckOut($user_id, $dt = null)
    {
        if ($dt !== null) {
            $stay = self::getStay($user_id, $dt);
            if ($stay !== false) {
                return $stay['check_out'];
            }
        }

        $sql = 'SELECT U.check_out ' .
        'FROM ' . App::con()->prefix() . 'user U ' .
        "WHERE U.user_id = '" . App::con()->escape($user_id) . "' ";

        $rs = App::con()->select($sql);

        return $rs->check_out;
    }

    /**
     * Gets the user stays (JSON encoded in db).
     *
     * @param      mixed   $user_id  The user identifier
     *
     * @return     array  The user stays.
     */
    public static function getUserStays($user_id)
    {
        $sql = 'SELECT U.stays ' .
        'FROM ' . App::con()->prefix() . 'user U ' .
        "WHERE U.user_id = '" . App::con()->escape($user_id) . "' ";

        $rs = App::con()->select($sql);
        if ($rs->stays) {
            $list = json_decode($rs->stays, true, 512, JSON_THROW_ON_ERROR);
            usort($list, fn ($a, $b) => ($a['check_out'] > $b['check_out']));

            return $list;
        }

        return $rs->stays;
    }
}
