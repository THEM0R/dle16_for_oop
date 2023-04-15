<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}

//####################################################################################################################
//    The definition of banned users and IP
//####################################################################################################################
//$banned_info = get_vars ( "banned" );
//
//if (!is_array ( $banned_info )) {
//
//  $banned_info = array ();
//
//  $db->query ( "SELECT * FROM " . USERPREFIX . "_banned" );
//  while ( $row = $db->get_row () ) {
//
//    if ($row['users_id']) {
//
//      $banned_info['users_id'][$row['users_id']] = array (
//        'users_id' => $row['users_id'],
//        'descr' => stripslashes ( $row['descr'] ),
//        'date' => $row['date'] );
//
//    } else {
//
//      if (count ( explode ( ".", $row['ip'] ) ) == 4 OR filter_var( $row['ip'] , FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) OR strpos($row['ip'], ":") !== false )
//        $banned_info['ip'][$row['ip']] = array (
//          'ip' => $row['ip'],
//          'descr' => stripslashes ( $row['descr'] ),
//          'date' => $row['date']
//        );
//      elseif (strpos ( $row['ip'], "@" ) !== false)
//        $banned_info['email'][$row['ip']] = array (
//          'email' => $row['ip'],
//          'descr' => stripslashes ( $row['descr'] ),
//          'date' => $row['date'] );
//      else $banned_info['name'][$row['ip']] = array (
//        'name' => $row['ip'],
//        'descr' => stripslashes ( $row['descr'] ),
//        'date' => $row['date'] );
//
//    }
//
//  }
//  set_vars ( "banned", $banned_info );
//  $db->free ();
//}
//
//$category_skin = "";

if ($category) $category_id = get_ID( $cat_info, $category );
else $category_id = false;

if ($category_id) $category_skin = $cat_info[$category_id]['skin'];

