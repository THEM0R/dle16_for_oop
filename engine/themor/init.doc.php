<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}

//####################################################################################################################
//     Definition of categories / Визначення категорій
//####################################################################################################################
$cat_info = get_vars ( "category" );


if (!is_array ( $cat_info )) {
  $cat_info = array ();

  $db->query ( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );

  while ( $row = $db->get_row () ) {

    if( !$row['active'] ) continue;

    $cat_info[$row['id']] = array ();

    foreach ( $row as $key => $value ) {
      $cat_info[$row['id']][$key] = stripslashes ( $value );
    }

    $cat_info[$row['id']]['newscount'] = 0;

  }
  set_vars ( "category", $cat_info );
  //pr1($cat_info);
}


$config['speedbar_separator'] = htmlspecialchars_decode( $config['speedbar_separator'], ENT_QUOTES);
$config['category_separator'] = htmlspecialchars_decode( $config['category_separator'], ENT_QUOTES);
$config['tags_separator'] = htmlspecialchars_decode( $config['tags_separator'], ENT_QUOTES);

if( $do == "download" ) {
  include_once (DLEPlugins::Check(ENGINE_DIR . '/download.php'));
  die();
} elseif($do == "go") {
  include_once (DLEPlugins::Check(ENGINE_DIR . '/go.php'));
  die();
} elseif($do == "opensearch") {
  include_once (DLEPlugins::Check(ENGINE_DIR . '/opensearch.php'));
  die();
} elseif(isset($_GET['mod']) AND $_GET['mod'] == "rss") {

  include_once (DLEPlugins::Check(ENGINE_DIR . '/rss.php'));

  die();
}

if( $config['allow_redirects'] ) {

  $redirects = get_vars( "redirects" );

  if( !is_array( $redirects ) ) {
    $redirects = array ();

    $db->query( "SELECT * FROM " . PREFIX . "_redirects ORDER BY id DESC" );

    while ( $row = $db->get_row() ) {

      if( strpos ( $row['from'], "*" ) !== false ) {

        $row['from'] = preg_quote(urldecode($row['from']), '%');
        $row['from'] = '%^'.str_replace('\*', '(.*)', $row['from']).'%i';
        $redirects['regex'][$row['from']] = $row['to'];

      } else {
        $row['from'] = urldecode($row['from']);
        $redirects['simple'][$row['from']] = urldecode($row['to']);
      }

    }

    set_vars( "redirects", $redirects );
    $db->free();
  }

  $uri = preg_replace( '#[/]+#i', '/', urldecode($_SERVER['REQUEST_URI']) );

  if(isset($redirects['simple']) AND is_array($redirects['simple']) AND count($redirects['simple']) AND isset($redirects['simple'][$uri]) ) {

    if( !check_same_domain($redirects['simple'][$uri]) OR !isset($_SESSION['is_redirect']) ) {

      $_SESSION['is_redirect'] = true;
      header("HTTP/1.0 301 Moved Permanently");
      header("Location: ". $redirects['simple'][$uri] );
      die("301 Redirect");

    }

  }

  if(isset($redirects['regex']) AND  is_array($redirects['regex']) AND count($redirects['regex']) ) {

    foreach ($redirects['regex'] as $key => $value) {

      if(preg_match($key, $uri)){

        if( !check_same_domain($value) OR !isset($_SESSION['is_redirect']) ) {

          $_SESSION['is_redirect'] = true;
          header("HTTP/1.0 301 Moved Permanently");
          header("Location: ". $value );
          die("301 Redirect");

        }
      }
    }
  }

  unset($_SESSION['is_redirect']);

}

if( $config['only_ssl'] AND !isSSL() AND !isset($_SESSION['is_redirect']) ) {
  $_SESSION['is_redirect'] = true;

  $_SERVER['REQUEST_URI'] = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, $config['charset'] );
  header("HTTP/1.0 301 Moved Permanently");
  header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
  die("Redirect");

} elseif( isset($_SESSION['is_redirect']) ) { unset($_SESSION['is_redirect']); }

$cron_time = get_vars ( "cron" );

if( isset($cron_time['locked']) AND $cron_time['locked'] AND $cron_time['time'] ) {

  $cron_time['lasttime'] = $cron_time['time'];
  $cron_time['time'] = $cron_time['successtime'];

}

if( !isset($cron_time['time']) ) $cron = 2;
elseif( isset($cron_time['time']) AND date ( "Y-m-d", $cron_time['time'] ) != date ( "Y-m-d", $_TIME )) $cron = 2;
elseif( isset($cron_time['time']) AND ( ($cron_time['time'] + (3600 * 2) ) < $_TIME) ) $cron = 1;

if ($cron) {

  include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/cron.php'));

}

