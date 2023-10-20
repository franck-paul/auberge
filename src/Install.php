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
use Dotclear\Core\Process;
use Dotclear\Database\Structure;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            // Init
            // Database schema
            $new_schema = new Structure(App::con(), App::con()->prefix());
            $new_schema->user
                ->room_id('integer', 0, true, 0)
                ->staff_role('varchar', 255, true, null)
                ->check_in('timestamp', 0, true, null)
                ->check_out('timestamp', 0, true, null)
                ->stays('text', 0, true, null);

            // Schema installation
            $current_schema = new Structure(App::con(), App::con()->prefix());
            $current_schema->synchronize($new_schema);
        } catch (Exception $e) {
            App::error()->add($e->getMessage());
        }

        return true;
    }
}
