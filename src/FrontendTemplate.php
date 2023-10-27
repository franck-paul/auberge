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
use Dotclear\App;
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
        $f    = App::frontend()->template()->getFilters($attr);
        $role = isset($attr['role']);

        return '<?php ' . "\n" .
            'App::frontend()->context()->player_id = App::frontend()->context()->exists("users") ? App::frontend()->context()->users->user_id : App::frontend()->context()->posts->user_id;' . "\n" .
            '$dt = App::frontend()->context()->exists("posts") ? App::frontend()->context()->posts->post_dt : null;' . "\n" .
            'App::frontend()->context()->room_id = ' . CoreData::class . '::getUserRoom(App::frontend()->context()->player_id, $dt);' . "\n" .
            'App::frontend()->context()->staff_role = ' . CoreData::class . '::getUserStaffRole(App::frontend()->context()->player_id, $dt);' . "\n" .
            'App::frontend()->context()->is_staff = (App::frontend()->context()->room_id > 999);' . "\n" .
            'if (App::frontend()->context()->is_staff) {' . "\n" .
            '  if (' . ($role ? 'true' : 'false') . ' && App::frontend()->context()->staff_role) {' . "\n" .
            '    $tmp = App::frontend()->context()->staff_role;' . "\n" .
            '  } else {' . "\n" .
            '    $tmp = (App::frontend()->context()->archives ? \'' . __('Staff') . '\' : \'' . __('Staff member') . '\');' . "\n" .
            '  }' . "\n" .
            '} else {' . "\n" .
            '  if (App::frontend()->context()->room_id) {' . "\n" .
            '      $tmp = sprintf(\'' . __('Room %s') . '\', App::frontend()->context()->room_id);' . "\n" .
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
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php ' . "\n" .
            'App::frontend()->context()->player_id = App::frontend()->context()->exists("users") ? App::frontend()->context()->users->user_id : App::frontend()->context()->posts->user_id;' . "\n" .
            '$dt = App::frontend()->context()->exists("posts") ? App::frontend()->context()->posts->post_dt : null;' . "\n" .
            'App::frontend()->context()->room_id = ' . CoreData::class . '::getUserRoom(App::frontend()->context()->player_id, $dt);' . "\n" .
            'App::frontend()->context()->is_staff = (App::frontend()->context()->room_id > 999);' . "\n" .
            'if (App::frontend()->context()->is_staff) {' . "\n" .
            '  $cls = sprintf(\'staff staff_%s\', App::frontend()->context()->room_id - 999);' . "\n" .
            '} else {' . "\n" .
            '  if (App::frontend()->context()->room_id) {' . "\n" .
            '    $cls = sprintf(\'room room_%s\', App::frontend()->context()->room_id);' . "\n" .
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
        $format = empty($attr['format']) ? App::blog()->settings()->system->date_format : addslashes($attr['format']);

        $list  = empty($attr['list']) ? __('<div>%s</div>') : $attr['list'];
        $item  = empty($attr['item']) ? __('<p>From %1$s to %2$s %3$s</p>') : $attr['item'];
        $staff = empty($attr['staff']) ? __('as %s') : $attr['staff'];
        $room  = empty($attr['room']) ? __('in room %s') : $attr['room'];

        $f = App::frontend()->template()->getFilters($attr);

        return '<?php ' . "\n" .
            'App::frontend()->context()->player_id = App::frontend()->context()->exists("users") ? App::frontend()->context()->users->user_id : App::frontend()->context()->posts->user_id;' . "\n" .
            'App::frontend()->context()->stays = ' . CoreData::class . '::getUserStays(App::frontend()->context()->player_id);' . "\n" .
            '$ret = "";' . "\n" .
            'if (App::frontend()->context()->stays) {' . "\n" .
            '  foreach(App::frontend()->context()->stays as App::frontend()->context()->stay) {' . "\n" .
            '    if (App::frontend()->context()->stay[\'room_id\'] > 999) {' . "\n" .
            '      $info = sprintf(\'' . addslashes($staff) . '\', ' . CoreHelper::class . '::getIdPosition(App::frontend()->context()->player_id, App::frontend()->context()->stay[\'position\']));' . "\n" .
            '    } else {' . "\n" .
            '      $info = sprintf(\'' . addslashes($room) . '\', App::frontend()->context()->stay[\'room_id\']);' . "\n" .
            '    }' . "\n" .
            '    if (strtotime(App::frontend()->context()->stay[\'check_in\']) <= time()) {' . "\n" .
            '      $ret .= sprintf(' . "\n" .
            '        \'' . addslashes($item) . '\',' . "\n" .
            Date::class . '::dt2str(\'' . $format . '\', App::frontend()->context()->stay[\'check_in\']),' . "\n" .
            Date::class . '::dt2str(\'' . $format . '\', App::frontend()->context()->stay[\'check_out\']),' . "\n" .
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
        $format = empty($attr['format']) ? App::blog()->settings()->system->date_format : addslashes($attr['format']);

        $f = App::frontend()->template()->getFilters($attr);

        return '<?php ' . "\n" .
            'App::frontend()->context()->player_id = App::frontend()->context()->exists("users") ? App::frontend()->context()->users->user_id : App::frontend()->context()->posts->user_id;' . "\n" .
            '$dt = App::frontend()->context()->exists("posts") ? App::frontend()->context()->posts->post_dt : null;' . "\n" .
            'App::frontend()->context()->check_in = ' . CoreData::class . '::getUserCheckIn(App::frontend()->context()->player_id, $dt);' . "\n" .
            'echo ' . sprintf($f, Date::class . '::dt2str(\'' . $format . '\', App::frontend()->context()->check_in)') . '; ?>';
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
        $format = empty($attr['format']) ? App::blog()->settings()->system->date_format : addslashes($attr['format']);

        $f = App::frontend()->template()->getFilters($attr);

        return '<?php ' . "\n" .
            'App::frontend()->context()->player_id = App::frontend()->context()->exists("users") ? App::frontend()->context()->users->user_id : App::frontend()->context()->posts->user_id;' . "\n" .
            '$dt = App::frontend()->context()->exists("posts") ? App::frontend()->context()->posts->post_dt : null;' . "\n" .
            'App::frontend()->context()->check_out = ' . CoreData::class . '::getUserCheckOut(App::frontend()->context()->player_id, $dt);' . "\n" .
            'echo ' . sprintf($f, Date::class . '::dt2str(\'' . $format . '\', App::frontend()->context()->check_out)') . '; ?>';
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
        '<?php if (App::frontend()->context()->comments->index()) { echo \'' . addslashes($ret) . "'; } ?>";
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
        '<?php if (App::frontend()->context()->pings->index()) { echo \'' . addslashes($ret) . "'; } ?>";
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
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, '(defined(\'DC_BLOG_SHORTNAME\') ? DC_BLOG_SHORTNAME : App::blog()->id())') . '; ?>';
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
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, CoreHelper::class . '::getTagLabel(App::frontend()->context()->meta->meta_id)') . '; ?>';
    }
}
