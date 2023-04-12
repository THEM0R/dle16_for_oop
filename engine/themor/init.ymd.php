<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}

if (isset ( $_GET['year'] )) {

  $year = intval($_GET['year']);

  if ($year < 1970) $year = 1970;
  if ($year > 2100) $year = 2100;

} else $year = '';

if (isset ( $_GET['month'] )) {

  $month = intval($_GET['month']);

  if($month < 1 OR $_GET['month'] > 12 ) $month = 1;

  $month = @$db->safesql ( sprintf("%02d", $month ) );

} else $month = '';

if (isset ( $_GET['day'] )) {
  $day = intval($_GET['day']);

  if($day < 1 OR $day > 31 ) $day = 1;

  $day = @$db->safesql ( sprintf("%02d", $day ) );

} else $day = '';

