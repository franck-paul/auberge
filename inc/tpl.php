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
            '$_ctx->room_id = aubergeData::getUserRoom($core, $_ctx->posts->user_id);' . "\n" .
            '$_ctx->staff_role = aubergeData::getUserStaffRole($core, $_ctx->posts->user_id);' . "\n" .
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
            '$_ctx->room_id = aubergeData::getUserRoom($core, $_ctx->posts->user_id);' . "\n" .
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

    /*dtd
    <!ELEMENT tpl:CommentIfEven - O -- displays value if comment is at an even position -->
    <!ATTLIST tpl:CommentIfEven
    return    CDATA    #IMPLIED    -- value to display in case of success (default: even)
    >
     */
    public function CommentIfEven($attr)
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
    public function PingIfEven($attr)
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
    public function BlogShortname($attr)
    {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        return '<?php echo ' . sprintf($f, '(defined(\'DC_BLOG_SHORTNAME\') ? DC_BLOG_SHORTNAME : $core->blog->id)') . '; ?>';
    }
}
