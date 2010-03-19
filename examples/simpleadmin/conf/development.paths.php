<?php
define('SS_SITE_ROOT',dirname(__DIR__));
define('SS_SITE_HTDOCS',SS_SITE_ROOT.'/htdocs');
define('SS_ROOT',dirname(dirname(SS_SITE_ROOT)));
set_include_path(get_include_path().':/home/jaanush/libs/php/active:'.SS_ROOT.'/lib:'.SS_SITE_ROOT);