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

class aubergeTpl
{
    /**
     * Cope with no_first_aside attribute
     *
     * @param      <type>  $core   The core
     * @param      <type>  $tag    The tag
     * @param      <type>  $args   The arguments
     */
    public static function publicBeforeContentFilter($core, $tag, $args)
    {
        if (isset($args['no_first_aside']) && (integer) $args['no_first_aside'] > 0) {
            if (strpos($args[0], '<aside>') === 0) {
                // Remove first aside if exists at beginning of string
                $args[0] = preg_replace('/<aside>(.*)?<\/aside>/msU', '', $args[0], 1);
            }
        }
    }

    /**
     * Template code for author room
     * Authors (in room) → room id from 1 to 999
     * Staff members → room id > 1000
     *
     * @param      <type>  $attr   The attribute
     *
     * @return     string  ( description_of_the_return_value )
     */
    public static function AuthorRoom($attr)
    {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        $role = isset($attr['role']);
        return '<?php '. "\n" .
            '$_ctx->player_id = $_ctx->exists("users") ? $_ctx->users->user_id : $_ctx->posts->user_id;' . "\n" .
            '$_ctx->room_id = aubergeData::getUserRoom($core, $_ctx->player_id);' . "\n" .
            '$_ctx->staff_role = aubergeData::getUserStaffRole($core, $_ctx->player_id);' . "\n" .
            '$_ctx->is_staff = ($_ctx->room_id > 999);' . "\n" .
            'if ($_ctx->is_staff) {' . "\n" .
            '  if (' . ($role ? 'true' : 'false') . ' && $_ctx->staff_role) {' . "\n" .
            '    $tmp = $_ctx->staff_role;' . "\n" .
            '  } else {' . "\n" .
            '    $tmp = ($_ctx->archives ? \'' . __('Staff') . '\' : \'' . __('Staff member') . '\');' . "\n" .
            '  }' . "\n" .
            '} else {' . "\n" .
            '  if ($_ctx->room_id) {' . "\n" .
            '      $tmp = sprintf(\'' . __('Room %s') . '\', $_ctx->room_id);' . "\n" .
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
     * @param      <type>  $attr   The attribute
     *
     * @return     string  ( description_of_the_return_value )
     */
    public static function AuthorRoomClass($attr)
    {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        return '<?php '. "\n" .
            '$_ctx->player_id = $_ctx->exists("users") ? $_ctx->users->user_id : $_ctx->posts->user_id;' . "\n" .
            '$_ctx->room_id = aubergeData::getUserRoom($core, $_ctx->player_id);' . "\n" .
            '$_ctx->is_staff = ($_ctx->room_id > 999);' . "\n" .
            'if ($_ctx->is_staff) {' . "\n" .
            '  $cls = sprintf(\'staff staff_%s\', $_ctx->room_id - 999);' . "\n" .
            '} else {' . "\n" .
            '  if ($_ctx->room_id) {' . "\n" .
            '    $cls = sprintf(\'room room_%s\', $_ctx->room_id);' . "\n" .
            '  } else {' . "\n" .
            '    $cls = \'\';' . "\n" .
            '  }' . "\n" .
            '}' . "\n" .
            'echo ' . sprintf($f, '$cls') . '; ?>';
    }

    /**
     * Template code for author stays
     *
     * @param      <type>  $attr   The attribute
     *
     * @return     string  ( description_of_the_return_value )
     */
    public static function AuthorCheckStays($attr)
    {
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        } else {
            $format = $GLOBALS['core']->blog->settings->system->date_format;
        }

        $list = !empty($attr['list']) ? $attr['list'] : __('<div>%s</div>');
        $item = !empty($attr['item']) ? $attr['item'] : __('<p>From %1$s to %2$s %3$s</p>');
        $staff = !empty($attr['staff']) ? $attr['staff'] : __('as %s');
        $room = !empty($attr['room']) ? $attr['room'] : __('in room %s');

        $f = $GLOBALS['core']->tpl->getFilters($attr);

        return '<?php '. "\n" .
            '$_ctx->player_id = $_ctx->exists("users") ? $_ctx->users->user_id : $_ctx->posts->user_id;' . "\n" .
            '$_ctx->stays = aubergeData::getUserStays($core, $_ctx->player_id);' . "\n" .
            '$ret = "";' . "\n" .
            'if ($_ctx->stays) {' . "\n" .
            '  foreach($_ctx->stays as $_ctx->stay) {' . "\n" .
            '    if ($_ctx->stay[\'room_id\'] > 999) {' . "\n" .
            '      $info = sprintf(\'' . addslashes($staff) . '\', auberge:utils::getIdPosition($_ctx->player_id, $_ctx->stay[\'position\']));' . "\n" .
            '    } else {' . "\n" .
            '      $info = sprintf(\'' . addslashes($room) . '\', $_ctx->stay[\'room_id\']);' . "\n" .
            '    }' . "\n" .
            '    $ret .= sprintf(' . "\n" .
            '      \'' . addslashes($item) . '\',' . "\n" .
            '      dt::dt2str(\'' . $format . '\', $_ctx->stay[\'check_in\']),' . "\n" .
            '      dt::dt2str(\'' . $format . '\', $_ctx->stay[\'check_out\']),' . "\n" .
            '      $info' . "\n" .
            '    );' . "\n" .
            '  }' . "\n" .
            '  echo ' . sprintf($f, 'sprintf(\'' . addslashes($list) . '\', $ret)') .';' . "\n" .
            '} ?>';
    }

    /**
     * Template code for author check-in
     *
     * @param      <type>  $attr   The attribute
     *
     * @return     string  ( description_of_the_return_value )
     */
    public static function AuthorCheckIn($attr)
    {
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        } else {
            $format = $GLOBALS['core']->blog->settings->system->date_format;
        }

        $f = $GLOBALS['core']->tpl->getFilters($attr);

        return '<?php '. "\n" .
            '$_ctx->player_id = $_ctx->exists("users") ? $_ctx->users->user_id : $_ctx->posts->user_id;' . "\n" .
            '$_ctx->check_in = aubergeData::getUserCheckIn($core, $_ctx->player_id);' . "\n" .
            'echo ' . sprintf($f, 'dt::dt2str(\'' . $format . '\', $_ctx->check_in)') . '; ?>';
    }

    /**
     * Template code for author check-out
     *
     * @param      <type>  $attr   The attribute
     *
     * @return     string  ( description_of_the_return_value )
     */
    public static function AuthorCheckOut($attr)
    {
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        } else {
            $format = $GLOBALS['core']->blog->settings->system->date_format;
        }

        $f = $GLOBALS['core']->tpl->getFilters($attr);

        return '<?php '. "\n" .
            '$_ctx->player_id = $_ctx->exists("users") ? $_ctx->users->user_id : $_ctx->posts->user_id;' . "\n" .
            '$_ctx->check_out = aubergeData::getUserCheckOut($core, $_ctx->player_id);' . "\n" .
            'echo ' . sprintf($f, 'dt::dt2str(\'' . $format . '\', $_ctx->check_out)') . '; ?>';
    }

    /*dtd
    <!ELEMENT tpl:CommentIfEven - O -- displays value if comment is at an even position -->
    <!ATTLIST tpl:CommentIfEven
    return    CDATA    #IMPLIED    -- value to display in case of success (default: even)
    >
     */
    public static function CommentIfEven($attr)
    {
        $ret = isset($attr['return']) ? $attr['return'] : 'even';
        $ret = html::escapeHTML($ret);

        return
        '<?php if ($_ctx->comments->index()%2) { ' .
        "echo '" . addslashes($ret) . "'; } ?>";
    }

    /*dtd
    <!ELEMENT tpl:PingIfEven - O -- displays value if trackback is at an even position -->
    <!ATTLIST tpl:PingIfEven
    return    CDATA    #IMPLIED    -- value to display in case of success (default: even)
    >
     */
    public static function PingIfEven($attr)
    {
        $ret = isset($attr['return']) ? $attr['return'] : 'even';
        $ret = html::escapeHTML($ret);

        return
        '<?php if ($_ctx->pings->index()%2) { ' .
        "echo '" . addslashes($ret) . "'; } ?>";
    }

    /*dtd
    <!ELEMENT tpl:BlogShortname - 0 -- Blog ID -->
     */
    public static function BlogShortname($attr)
    {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        return '<?php echo ' . sprintf($f, '(defined(\'DC_BLOG_SHORTNAME\') ? DC_BLOG_SHORTNAME : $core->blog->id)') . '; ?>';
    }

    /*dtd
    <!ELEMENT tpl:BlogNbEntriesFirstPage - O -- Number of entries for 1st page -->
     */
    public static function BlogNbEntriesFirstPage($attr)
    {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        return '<?php echo ' . sprintf($f, '$core->blog->settings->system->nb_post_for_home') . '; ?>';
    }

    /*dtd
    <!ELEMENT tpl:BlogNbEntriesPerPage - O -- Number of entries per page -->
     */
    public static function BlogNbEntriesPerPage($attr)
    {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        return '<?php echo ' . sprintf($f, '$core->blog->settings->system->nb_post_per_page') . '; ?>';
    }

    public static function TagLabel($attr)
    {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        return '<?php echo ' . sprintf($f, 'aubergeUtils::getTagLabel($_ctx->meta->meta_id)') . '; ?>';
    }
}
