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

use ArrayObject;
use dcCore;
use Dotclear\Helper\Date;
use Dotclear\Helper\Html\Html;

class FrontendTemplate
{
    /**
     * Template code for author room
     * Authors (in room) → room id from 1 to 999
     * Staff members → room id > 1000
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function AuthorRoom(ArrayObject $attr)
    {
        $f    = dcCore::app()->tpl->getFilters($attr);
        $role = isset($attr['role']);

        return '<?php ' . "\n" .
            'dcCore::app()->ctx->player_id = dcCore::app()->ctx->exists("users") ? dcCore::app()->ctx->users->user_id : dcCore::app()->ctx->posts->user_id;' . "\n" .
            '$dt = dcCore::app()->ctx->exists("posts") ? dcCore::app()->ctx->posts->post_dt : null;' . "\n" .
            'dcCore::app()->ctx->room_id = ' . CoreData::class . '::getUserRoom(dcCore::app(), dcCore::app()->ctx->player_id, $dt);' . "\n" .
            'dcCore::app()->ctx->staff_role = ' . CoreData::class . '::getUserStaffRole(dcCore::app(), dcCore::app()->ctx->player_id, $dt);' . "\n" .
            'dcCore::app()->ctx->is_staff = (dcCore::app()->ctx->room_id > 999);' . "\n" .
            'if (dcCore::app()->ctx->is_staff) {' . "\n" .
            '  if (' . ($role ? 'true' : 'false') . ' && dcCore::app()->ctx->staff_role) {' . "\n" .
            '    $tmp = dcCore::app()->ctx->staff_role;' . "\n" .
            '  } else {' . "\n" .
            '    $tmp = (dcCore::app()->ctx->archives ? \'' . __('Staff') . '\' : \'' . __('Staff member') . '\');' . "\n" .
            '  }' . "\n" .
            '} else {' . "\n" .
            '  if (dcCore::app()->ctx->room_id) {' . "\n" .
            '      $tmp = sprintf(\'' . __('Room %s') . '\', dcCore::app()->ctx->room_id);' . "\n" .
            '  } else {' . "\n" .
            '      $tmp = \'\';' . "\n" .
            '  }' . "\n" .
            '}' . "\n" .
            'echo ' . sprintf($f, '$tmp') . '; ?>';
    }

    /**
     * Template code for author room class
     * Authors (in room) → room id from 1 to 999 : return "room room_nnn"
     * Staff members → room id > 1000 : return "staff staff_nnn" with nnn = room_id - 999
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function AuthorRoomClass(ArrayObject $attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php ' . "\n" .
            'dcCore::app()->ctx->player_id = dcCore::app()->ctx->exists("users") ? dcCore::app()->ctx->users->user_id : dcCore::app()->ctx->posts->user_id;' . "\n" .
            '$dt = dcCore::app()->ctx->exists("posts") ? dcCore::app()->ctx->posts->post_dt : null;' . "\n" .
            'dcCore::app()->ctx->room_id = ' . CoreData::class . '::getUserRoom(dcCore::app(), dcCore::app()->ctx->player_id, $dt);' . "\n" .
            'dcCore::app()->ctx->is_staff = (dcCore::app()->ctx->room_id > 999);' . "\n" .
            'if (dcCore::app()->ctx->is_staff) {' . "\n" .
            '  $cls = sprintf(\'staff staff_%s\', dcCore::app()->ctx->room_id - 999);' . "\n" .
            '} else {' . "\n" .
            '  if (dcCore::app()->ctx->room_id) {' . "\n" .
            '    $cls = sprintf(\'room room_%s\', dcCore::app()->ctx->room_id);' . "\n" .
            '  } else {' . "\n" .
            '    $cls = \'\';' . "\n" .
            '  }' . "\n" .
            '}' . "\n" .
            'echo ' . sprintf($f, '$cls') . '; ?>';
    }

    /**
     * Template code for author stays
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function AuthorCheckStays(ArrayObject $attr)
    {
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        } else {
            $format = dcCore::app()->blog->settings->system->date_format;
        }

        $list  = !empty($attr['list']) ? $attr['list'] : __('<div>%s</div>');
        $item  = !empty($attr['item']) ? $attr['item'] : __('<p>From %1$s to %2$s %3$s</p>');
        $staff = !empty($attr['staff']) ? $attr['staff'] : __('as %s');
        $room  = !empty($attr['room']) ? $attr['room'] : __('in room %s');

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php ' . "\n" .
            'dcCore::app()->ctx->player_id = dcCore::app()->ctx->exists("users") ? dcCore::app()->ctx->users->user_id : dcCore::app()->ctx->posts->user_id;' . "\n" .
            'dcCore::app()->ctx->stays = ' . CoreData::class . '::getUserStays(dcCore::app(), dcCore::app()->ctx->player_id);' . "\n" .
            '$ret = "";' . "\n" .
            'if (dcCore::app()->ctx->stays) {' . "\n" .
            '  foreach(dcCore::app()->ctx->stays as dcCore::app()->ctx->stay) {' . "\n" .
            '    if (dcCore::app()->ctx->stay[\'room_id\'] > 999) {' . "\n" .
            '      $info = sprintf(\'' . addslashes($staff) . '\', ' . CoreHelper::class . '::getIdPosition(dcCore::app()->ctx->player_id, dcCore::app()->ctx->stay[\'position\']));' . "\n" .
            '    } else {' . "\n" .
            '      $info = sprintf(\'' . addslashes($room) . '\', dcCore::app()->ctx->stay[\'room_id\']);' . "\n" .
            '    }' . "\n" .
            '    if (strtotime(dcCore::app()->ctx->stay[\'check_in\']) <= time()) {' . "\n" .
            '      $ret .= sprintf(' . "\n" .
            '        \'' . addslashes($item) . '\',' . "\n" .
            Date::class . '::dt2str(\'' . $format . '\', dcCore::app()->ctx->stay[\'check_in\']),' . "\n" .
            Date::class . '::dt2str(\'' . $format . '\', dcCore::app()->ctx->stay[\'check_out\']),' . "\n" .
            '        $info' . "\n" .
            '      );' . "\n" .
            '    }' . "\n" .
            '  }' . "\n" .
            '  echo ' . sprintf($f, 'sprintf(\'' . addslashes($list) . '\', $ret)') . ';' . "\n" .
            '} ?>';
    }

    /**
     * Template code for author check-in
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function AuthorCheckIn(ArrayObject $attr)
    {
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        } else {
            $format = dcCore::app()->blog->settings->system->date_format;
        }

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php ' . "\n" .
            'dcCore::app()->ctx->player_id = dcCore::app()->ctx->exists("users") ? dcCore::app()->ctx->users->user_id : dcCore::app()->ctx->posts->user_id;' . "\n" .
            '$dt = dcCore::app()->ctx->exists("posts") ? dcCore::app()->ctx->posts->post_dt : null;' . "\n" .
            'dcCore::app()->ctx->check_in = ' . CoreData::class . '::getUserCheckIn(dcCore::app(), dcCore::app()->ctx->player_id, $dt);' . "\n" .
            'echo ' . sprintf($f, Date::class . '::dt2str(\'' . $format . '\', dcCore::app()->ctx->check_in)') . '; ?>';
    }

    /**
     * Template code for author check-out
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function AuthorCheckOut(ArrayObject $attr)
    {
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        } else {
            $format = dcCore::app()->blog->settings->system->date_format;
        }

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php ' . "\n" .
            'dcCore::app()->ctx->player_id = dcCore::app()->ctx->exists("users") ? dcCore::app()->ctx->users->user_id : dcCore::app()->ctx->posts->user_id;' . "\n" .
            '$dt = dcCore::app()->ctx->exists("posts") ? dcCore::app()->ctx->posts->post_dt : null;' . "\n" .
            'dcCore::app()->ctx->check_out = ' . CoreData::class . '::getUserCheckOut(dcCore::app(), dcCore::app()->ctx->player_id, $dt);' . "\n" .
            'echo ' . sprintf($f, Date::class . '::dt2str(\'' . $format . '\', dcCore::app()->ctx->check_out)') . '; ?>';
    }

    /**
     * Template code for even comments
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function CommentIfEven(ArrayObject $attr)
    {
        $ret = $attr['return'] ?? 'even';
        $ret = Html::escapeHTML($ret);

        return
        '<?php if (dcCore::app()->ctx->comments->index()) { ' .
        "echo '" . addslashes($ret) . "'; } ?>";
    }

    /**
     * Template code for even pings
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function PingIfEven(ArrayObject $attr)
    {
        $ret = $attr['return'] ?? 'even';
        $ret = Html::escapeHTML($ret);

        return
        '<?php if (dcCore::app()->ctx->pings->index()) { ' .
        "echo '" . addslashes($ret) . "'; } ?>";
    }

    /**
     * Template code for blog short name
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function BlogShortname(ArrayObject $attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, '(defined(\'DC_BLOG_SHORTNAME\') ? DC_BLOG_SHORTNAME : dcCore::app()->blog->id)') . '; ?>';
    }

    /**
     * Template code for tag label
     *
     * @param      ArrayObject  $attr   The attribute
     *
     * @return     string
     */
    public static function TagLabel(ArrayObject $attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, CoreHelper::class . '::getTagLabel(dcCore::app()->ctx->meta->meta_id)') . '; ?>';
    }
}
