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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$new_version = dcCore::app()->plugins->moduleInfo('auberge', 'version');
$old_version = dcCore::app()->getVersion('auberge');

if (version_compare($old_version, $new_version, '>=')) {
    return;
}

try {
    // Database schema
    $s = new dbStruct(dcCore::app()->con, dcCore::app()->prefix);
    $s->user
        ->room_id('integer', 0, true, 0)
        ->staff_role('varchar', 255, true, null)
        ->check_in('timestamp', 0, true, null)
        ->check_out('timestamp', 0, true, null)
        ->stays('text', 0, true, null);

    // Schema installation
    $si      = new dbStruct(dcCore::app()->con, dcCore::app()->prefix);
    $changes = $si->synchronize($s);

    dcCore::app()->setVersion('auberge', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
