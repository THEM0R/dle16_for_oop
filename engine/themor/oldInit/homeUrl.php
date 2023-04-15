<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}

/*
 * перевірка чи є юрл сайту
 */

if ( !$config['http_home_url'] ) {
  $config['http_home_url'] = explode ( "index.php", $_SERVER['PHP_SELF'] );
  $config['http_home_url'] = reset ( $config['http_home_url'] );
  $config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];
}

/*
 * перевірка чи є ссл юрл сайту
 */

if( isSSL() AND stripos( $config['http_home_url'], 'http://' ) !== false ) {
  $config['http_home_url'] = str_replace( "http://", "https://", $config['http_home_url'] );
}

if (substr ( $config['http_home_url'], - 1, 1 ) != '/') $config['http_home_url'] .= '/';

$PHP_SELF = $config['http_home_url'] . "index.php";

