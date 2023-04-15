<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../../' );
  die( "Hacking attempt!" );
}

/*
 * підключення файлів конфігу
 */
@include_once (ENGINE_DIR . '/data/config.php');


/*
 * перевірка
 * Datalife Engine не включається. Будь ласка, запустіть install.php
 */

if ( !$config['version_id'] ) {

  if ( file_exists(ROOT_DIR . '/install.php') AND !file_exists(ENGINE_DIR . '/data/config.php') ) {

    header( "Location: ".str_replace(basename($_SERVER['PHP_SELF']),"install.php",$_SERVER['PHP_SELF']) );
    die ( "Datalife Engine not installed. Please run install.php" );

  } else {

    die ( "Datalife Engine not installed. Please run install.php" );
  }

}

/*
 * перевірка
 * Datalife Engine вимагає PHP версії 7.4 або новішої.
 * Вам потрібно оновити версію PHP на вашому сервері.
 */
if( version_compare(phpversion(), '7.4', '<') ) {
  die ( "Datalife Engine required PHP version 7.4 or greater. You need upgrade PHP version on your server." );
}

@ini_set('pcre.recursion_limit', 10000000 );
@ini_set('pcre.backtrack_limit', 10000000 );
@ini_set('pcre.jit', '0');


if( isset($config['display_php_errors']) AND $config['display_php_errors'] ) {
  @ini_set('display_errors', '1');
  @ini_set('display_startup_errors', '1');
  @ini_set('html_errors', '0');
} else {
  @ini_set('display_errors', '0');
  @ini_set('display_startup_errors', '0');
}

require_once (ENGINE_DIR . '/classes/mysql.php');
require_once (ENGINE_DIR . '/data/dbconfig.php');