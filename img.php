<?php
/**
 * @license GPL v2 (http://www.gnu.org/licenses/gpl.html)
 * @author  Willi Schönborn (w.schoenborn@googlemail.com)
 */

// On debian the plugins are in a directory hierarchy
// (/var/lib/dokuwiki/lib/plugins/...) separate from the dokuwiki install
// (/usr/share/dokuwiki/...) and ../../../inc is a symlink. So avoid defining
// DOKU_INC below and instead use a separate variable (MY_INC to include
// the dokuwiki init.php in order to let the latter define DOKU_INC from the
// correct directory hierarchy.
if (defined('DOKU_INC')) {
   define('MY_INC', DOKU_INC . 'inc');
} else {
   define('MY_INC', realpath(dirname(__FILE__) . '/../../../inc'));
}
define('NOSESSION', true);
require_once(MY_INC . '/init.php');

$data = $_REQUEST;
$plugin = plugin_load('syntax', 'plantuml');
$cache  = $plugin->_imgfile($data);

if ($cache) {
    header('Content-Type: image/png;');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + max($conf['cachetime'], 3600)) . ' GMT');
    header('Cache-Control: public, proxy-revalidate, no-transform, max-age=' . max($conf['cachetime'], 3600));
    header('Pragma: public');
    http_conditionalRequest($time);
    echo io_readFile($cache, false);
} else {
    header('HTTP/1.0 404 Not Found');
    header('Content-Type: image/png');
    echo io_readFile('res/file-broken/file-broken.png', false);
}
