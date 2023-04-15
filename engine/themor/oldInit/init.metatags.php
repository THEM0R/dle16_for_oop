<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}


//####################################################################################################################
//    meta tags and titles for pages
//####################################################################################################################
$custom_metatags = array ();
$page_header_info = array();

if( $config['allow_own_meta'] ) {
  $custom_metatags = get_vars( "metatags" );

  if( !is_array( $custom_metatags ) ) {
    $custom_metatags = array ();

    $db->query( "SELECT * FROM " . PREFIX . "_metatags ORDER BY id DESC" );

    while ( $row = $db->get_row() ) {

      if( strpos ( $row['url'], "*" ) !== false ) {

        $row['url'] = preg_quote(urldecode($row['url']), '%');
        $row['url'] = '%^'.str_replace('\*', '(.*)', $row['url']).'%i';

        $custom_metatags['regex'][$row['url']] = array('title' => $row['title'], 'description' => $row['description'], 'keywords' => $row['keywords'], 'page_title' => $row['page_title'], 'robots' => $row['robots'], 'page_description' => stripslashes($row['page_description']));

      } else {

        $row['url'] = urldecode($row['url']);
        $custom_metatags['simple'][$row['url']] = array('title' => $row['title'], 'description' => $row['description'], 'keywords' => $row['keywords'], 'page_title' => $row['page_title'], 'robots' => $row['robots'], 'page_description' => stripslashes($row['page_description']));

      }

    }

    set_vars( "metatags", $custom_metatags );
    $db->free();
  }

  $r_uri = preg_replace( '#[/]+#i', '/', urldecode($_SERVER['REQUEST_URI']) );

  $url_charset = detect_encoding($r_uri);

  if ( $url_charset AND $url_charset != $config['charset'] ) {

    if( function_exists( 'mb_convert_encoding' ) ) {

      $r_uri = mb_convert_encoding( $r_uri, $config['charset'], $url_charset );

    } elseif( function_exists( 'iconv' ) ) {

      $r_uri = iconv($url_charset, $config['charset'], $r_uri);

    }

  }

  if(isset($custom_metatags['simple']) AND is_array($custom_metatags['simple']) AND count($custom_metatags['simple']) AND isset($custom_metatags['simple'][$r_uri]) AND $custom_metatags['simple'][$r_uri] ) {
    if( $custom_metatags['simple'][$r_uri]['page_title'] ) $page_header_info['title'] = $custom_metatags['simple'][$r_uri]['page_title'];
    if( $custom_metatags['simple'][$r_uri]['page_description'] ) $page_header_info['description'] = $custom_metatags['simple'][$r_uri]['page_description'];
  }

  if(isset($custom_metatags['regex']) AND is_array($custom_metatags['regex']) AND count($custom_metatags['regex'])) {
    foreach ($custom_metatags['regex'] as $key => $value) {
      if(preg_match($key, $r_uri)){
        if( $value['page_title'] ) $page_header_info['title'] = $value['page_title'];
        if( $value['page_description'] ) $page_header_info['description'] = $value['page_description'];
      }
    }
  }

}

