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
use Dotclear\Core\Frontend\Url;
use Dotclear\Helper\Network\Http;

class FrontendUrl extends Url
{
    /**
     * URL Handler for single-page archive with redirect to page anchor if necessary
     *
     * @param      null|string  $args   The arguments
     */
    public static function archive(?string $args): void
    {
        $anchor = '';
        if (preg_match('|^/(\d{4})/(\d{2})$|', $args, $m)) {
            $anchor = '#Y' . $m[1] . '-M' . $m[2];
            Http::redirect(App::blog()->url() . App::url()->getURLFor('archive') . $anchor);
        }

        self::serveDocument('archive.html');
    }
}
