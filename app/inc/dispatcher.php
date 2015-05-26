<?php
namespace Typolib;

use Transvision\Utils;

// Init locale with browser locale if we support it; first locale we support, otherwise.
$l10n = new \tinyl10n\ChooseLocale(Locale::getLocaleList());
$locale = $l10n->getCompatibleLocale();

$success = $errors = [];

$template     = true;
$page         = $urls[$url['path']];
$extra        = null;
$show_title   = true;

switch ($url['path']) {
    case '/':
        $controller = 'view';
        $page_title = 'View rules';
        $page_descr = '';
        break;
    case 'api':
        $template = false;
        $controller = 'api';
        $show_title = false;
        break;
    case 'insert':
        $controller = 'insert';
        $page_title = 'Create a code';
        $page_descr = '';
        break;
    case 'test':
        $view       = 'test';
        $page_title = 'Test page';
        $page_descr = 'This is a page for test purposes only.';
        break;
    case 'view':
        $controller = 'view';
        $page_title = 'View rules';
        $page_descr = '';
        break;
    case 'check':
        $controller = 'check';
        $page_title = 'Check text';
        $page_descr = '';
        break;
    default:
        $controller = 'view';
        $page_title = 'Adding new rules';
        $page_descr = '';
        break;
}

if ($template) {
    ob_start();

    if (isset($view)) {
        include VIEWS . $view . '.php';
    } else {
        include CONTROLLERS . $controller . '.php';
    }

    $content = ob_get_contents();
    ob_end_clean();

    // display the page
    require_once VIEWS . 'templates/base.php';
} else {
    if (isset($view)) {
        include VIEWS . $view . '.php';
    } else {
        include CONTROLLERS . $controller . '.php';
    }
}

// Log script performance in PHP integrated developement server console
Utils::logScriptPerformances();
