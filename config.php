<?php
// The project id is the google application project short code. Change this and the world is a
// different place as this is where all the management and identity stuff happend operationally.
$project_id = "minertor";

$app_email = "noreply@bogoff.com";

// When running in non-localhost, you can force these to false so that you can debug javascript
$compress_js = true;

$db_server = "localhost";
$db_name = "mtm";
$db_user = "mtm_user";
$db_pass = "mtm_passwd";

// Configure the logging variables
define("LL_NONE", 0);
define("LL_SYSTEM", 0);
define("LL_SYS", 0);
define("LL_ERROR", 1);
define("LL_ERR", 1);
define("LL_WRN", 2);
define("LL_WARN", 2);
define("LL_WARNING", 2);
define("LL_INF", 3);
define("LL_INFO", 3);
define("LL_DBG", 4);
define("LL_DEBUG", 4);
define("LL_EDBG", 5);
define("LL_EDEBUG", 5);
define("LL_XDBG", 6);
define("LL_XDEBUG", 6);

// Fundamentally disable logging in the system - can be overriden in config_[hostname].php;
$log_level = LL_SYS;

// This allows for a title that discrimiantes it from a producrtion page when on localhost
$local_monika = " (LH)";
