<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2023 SoftNews Media Group
=====================================================
 This code is protected by copyright
=====================================================
 File: admin.php
-----------------------------------------------------
 Use: adminpanel
=====================================================
*/

ob_start();
ob_implicit_flush(false);

error_reporting ( E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );

define('DATALIFEENGINE', true);
define('ROOT_DIR', dirname(__FILE__));
define('ENGINE_DIR', ROOT_DIR . '/engine');


/*
 * theMor
 */
define('MOR_DIR', ENGINE_DIR . '/themor');

// init vars the.mor
require_once (MOR_DIR . '/dump.php');

/*
 * підключення файлів конфігу
 */
include_once (MOR_DIR . '/init.php');

require_once(ENGINE_DIR . '/classes/plugins.class.php');
require_once(ENGINE_DIR . '/inc/include/init.php');
