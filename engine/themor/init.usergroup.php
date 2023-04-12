<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}


//################# Definition of user groups
$user_group = get_vars ( "usergroup" );

if (!is_array( $user_group )) {
  $user_group = array ();

  $db->query ( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );

  while ( $row = $db->get_row () ) {

    $user_group[$row['id']] = array ();

    foreach ( $row as $key => $value ) {
      $user_group[$row['id']][$key] = stripslashes($value);
    }

  }
  set_vars ( "usergroup", $user_group );
  $db->free ();
}

