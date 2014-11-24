<?php
define('APPLICATION_PATH', realpath(dirname(__FILE__). "/../"));
define('APPLICATION_ENV', 'development');

defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

define("ENVIRONMENT", "DEVELOPMENT");
define("SITE_REVISION", 2);