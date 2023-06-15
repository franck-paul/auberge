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

use dcCore;
use dcNsProcess;
use Dotclear\Database\Structure;
use Exception;

class Install extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::checkContext(My::INSTALL);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        try {
            // Init
            // Database schema
            $new_schema = new Structure(dcCore::app()->con, dcCore::app()->prefix);
            $new_schema->user
                ->room_id('integer', 0, true, 0)
                ->staff_role('varchar', 255, true, null)
                ->check_in('timestamp', 0, true, null)
                ->check_out('timestamp', 0, true, null)
                ->stays('text', 0, true, null);

            // Schema installation
            $current_schema = new Structure(dcCore::app()->con, dcCore::app()->prefix);
            $current_schema->synchronize($new_schema);
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
