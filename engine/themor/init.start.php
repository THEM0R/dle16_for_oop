<?php
if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}

pr($_GET);

if (isset ( $_GET['catalog'] )) {

  $catalog = strip_tags ( str_replace ( '/', '', urldecode ( (string)$_GET['catalog'] ) ) );
  $catalog = $db->safesql ( dle_substr ( trim($catalog), 0, 3, $config['charset'] ) );

} else $catalog = '';

if (isset ( $_GET['user'] )) {

  $user = strip_tags ( str_replace ( '/', '', urldecode ( (string)$_GET['user'] ) ) );
  $user = $db->safesql ( $user );

  if( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\@|\#|\/|\\\|\&\~\*\+]/", $user ) ) $user="";

} else $user = '';

// категорія
if (isset ( $_GET['category'] )) {

  $_GET['category'] = (string)$_GET['category'];
  // new
  //if (substr ( $_GET['category'], - 1, 1 ) == '/') $_GET['category'] = substr ( $_GET['category'], 0, - 1 );

  // обрізаєм /
  $mor_cat = substr ( $_GET['category'], - 1, 1 );

  //pr1($_GET['category']);

  if ( $mor_cat == '/' ) {
    $_GET['category'] = substr($_GET['category'], 0, -1);
  }
  $category = explode ( '/', $_GET['category'] );

  $category = end ( $category );

  //pr1($db->safesql ( strip_tags ( $category ) ));

  $category = $db->safesql ( strip_tags ( $category ) );

  //exit;

} else $category = '';

//pr1($_REQUEST);

if (isset ( $_GET['news_name'] )) $news_name = @$db->safesql ( strip_tags ( str_replace ( '/', '', (string)$_GET['news_name'] ) ) ); else $news_name = '';
if (isset ( $_GET['newsid'] )) $newsid = intval ( $_GET['newsid'] ); else $newsid = 0;
if (isset ( $_GET['cstart'] )) $cstart = intval ( $_GET['cstart'] ); else $cstart = 0;
if (isset ( $_GET['news_page'] )) $news_page = intval ( $_GET['news_page'] ); else $news_page = 0;

if ($cstart < 1) $cstart = 0;
if ($cstart > 9999999) $cstart = 9999999;

if( isset( $_REQUEST['action'] ) and $_REQUEST['action'] == "mobiledisable" ) { $_SESSION['mobile_disable'] = 1; $_SESSION['mobile_enable'] = 0; }
if( isset( $_REQUEST['action'] ) and $_REQUEST['action'] == "mobile" ) { $_SESSION['mobile_enable'] = 1; $_SESSION['mobile_disable'] = 0;}
if( !isset( $_SESSION['mobile_disable'] ) ) $_SESSION['mobile_disable'] = 0;
if( !isset( $_SESSION['mobile_enable'] ) ) $_SESSION['mobile_enable'] = 0;

if( !isset ( $do ) AND isset ($_REQUEST['do']) ) $do = totranslit ( $_REQUEST['do'] ); elseif(isset ( $do )) $do = totranslit ( $do ); else $do = '';

if( !isset ( $subaction ) AND isset ($_REQUEST['subaction']) ) $subaction = totranslit ($_REQUEST['subaction']); elseif(isset($subaction)) $subaction = totranslit($subaction); else $subaction = '';
if( isset ($_REQUEST['doaction']) ) $doaction = totranslit ($_REQUEST['doaction']); else $doaction = "";
if( $do == "tags" AND !$_GET['tag'] ) $do = "alltags";

$dle_module = $do;
if (!$do AND !$subaction AND $year) $dle_module = "date";
elseif (!$do AND isset($_GET['catalog'])) $dle_module = "catalog";
elseif (!$do) $dle_module = $subaction;
if (!$subaction AND $newsid) $dle_module = "showfull";
$dle_module = $dle_module ? $dle_module : "main";

if( $config['start_site'] == 3 AND $dle_module == "main" AND  ( !isset($_GET['mod']) OR (isset($_GET['mod']) AND  $_GET['mod'] != "rss") ) ) {
  $_GET['do'] = "static";
  $_REQUEST['do'] = "static";
  $_GET['page'] = "main";
  $_REQUEST['page'] = "main";
  $do = "static";
}