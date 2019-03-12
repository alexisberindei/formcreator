<?php
// fix empty CFG_GLPI on boostrap; see https://github.com/sebastianbergmann/phpunit/issues/325
global $CFG_GLPI, $GLPI_CACHE;

//disable session cookies
ini_set('session.use_cookies', 0);
ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

require_once __DIR__ . '/../vendor/autoload.php';

define('TEST_PLUGIN_NAME', 'formcreator');

// glpi/inc/oolbox.class.php tests TU_USER to decide if it warns or not about mcrypt extension
define('TU_USER', '_test_user');

define('GLPI_ROOT', realpath(__DIR__ . '/../../../'));
define('GLPI_CONFIG_DIR', GLPI_ROOT . "/tests");
define('GLPI_CACHE_DIR', GLPI_ROOT . '/tests/files/_cache');
if (!file_exists(GLPI_CONFIG_DIR . '/config_db.php')) {
   echo "config_db.php missing. Did GLPI successfully initialized ?\n";
   exit(1);
}

define('GLPI_LOG_DIR', __DIR__ . '/logs');
@mkdir(GLPI_LOG_DIR);
if (!defined('STDERR')) {
   define('STDERR', fopen(GLPI_LOG_DIR . '/stderr.log', 'w'));
}

// Giving --debug argument to atoum will be detected by GLPI too
// the error handler in Toolbox may output to stdout a message and break process communication
// in atoum
$key = array_search('--debug', $_SERVER['argv']);
if ($key) {
   //unset($_SERVER['argv'][$key]);
}

include (GLPI_ROOT . "/inc/includes.php");

//init cache
$GLPI_CACHE = Config::getCache('cache_db');

// If GLPI debug mode is disabled, atoum cannot produce backtaces
//\Toolbox::setDebugMode(Session::DEBUG_MODE);
