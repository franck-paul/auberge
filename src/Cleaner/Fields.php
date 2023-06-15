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

namespace Dotclear\Plugin\auberge\Cleaner;

use dcCore;
use Dotclear\Plugin\Uninstaller\{
    ActionDescriptor,
    CleanerDescriptor,
    CleanerParent,
    ValueDescriptor
};

/**
 * Cleaner for Adaptive images cache directory.
 */
class Fields extends CleanerParent
{
    public function __construct()
    {
        parent::__construct(new CleanerDescriptor(
            id:   'fields',
            name: __('Fields'),
            desc: __('All database fields in table'),
            actions: [
                // delete a $ns table:field.
                new ActionDescriptor(
                    id:      'delete',
                    select:  __('delete selected fields'),
                    query:   __('delete "%s" fields'),
                    success: __('"%s" table deleted'),
                    error:   __('Failed to delete "%s" table'),
                    default: true
                ),
            ]
        ));
    }

    public function distributed(): array
    {
        return [
        ];
    }

    public function values(): array
    {
        $res = [];

        return $res;
    }

    public function execute(string $action, string $ns): bool
    {
        if ($action === 'delete') {
            [$table, $field] = explode(PATH_SEPARATOR, $ns);

            if ($table && $field) {
                $sql = 'ALTER TABLE ' . dcCore::app()->blog->prefix . $table . ' DROP COLUMN ' . $field;
                dcCore::app()->con->execute($sql);
            }

            return true;
        }

        return false;
    }
}
