<?php
namespace Typolib;

use Transvision\Utils;

$locale_selector = Utils::getHtmlSelectOptions(
                                Locale::getLocaleList(),
                                $locale
                            );
