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
 File: init.php
-----------------------------------------------------
 Use: Initialization
=====================================================
*/

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../' );
	die( "Hacking attempt!" );
}

require_once (DLEPlugins::Check(ENGINE_DIR . '/modules/functions.php'));


// init vars the.mor
require_once (ENGINE_DIR . '/themor/init.vars.php');

// init cache the.mor
require_once (ENGINE_DIR . '/themor/init.cache.php');

// init home url the.mor
require_once (ENGINE_DIR . '/themor/init.httphomeurl.php');

// init year month day the.mor
require_once (ENGINE_DIR . '/themor/init.ymd.php');

// init start the.mor
require_once (ENGINE_DIR . '/themor/init.start.php');

// init usergroup the.mor
require_once (ENGINE_DIR . '/themor/init.usergroup.php');

// init doc the.mor
require_once (ENGINE_DIR . '/themor/init.doc.php');

// init metatags the.mor
//require_once (ENGINE_DIR . '/themor/init.metatags.php');

// init ctn the.mor
//require_once (ENGINE_DIR . '/themor/init.ctn.php');

// init dob the.mor
require_once (ENGINE_DIR . '/themor/init.dob.php');

// init skin the.mor
require_once (ENGINE_DIR . '/themor/init.skin.php');
// #################################

//pr1($config);

//if ($config['site_offline']) include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/offline.php'));

//if ($config['allow_calendar'] OR $config['allow_archives']) include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/calendar.php'));

if ($config['rss_informer']) include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/rssinform.php'));

//if ($config['allow_links']) include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/links.php'));

include_once (DLEPlugins::Check(ROOT_DIR . '/engine/engine.php'));

if ($config['allow_topnews']) include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/topnews.php'));

if ($config['allow_votes'] ) include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/vote.php'));

if ($config['allow_tags']) include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/tagscloud.php'));

include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/main.php'));
