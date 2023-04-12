<?php
if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}


dle_session();
check_xss();

if( $config['date_adjust'] ) {

  date_default_timezone_set ( $config['date_adjust'] );

}

$Timer = new microTimer();
$cron = false;
$_TIME = time();
$pm_alert = "";
$twofactor_alert = "";
$ajax = "";
$allow_comments_ajax = false;
$_DOCUMENT_DATE = false;
$user_query = "";
$static_result = array ();
$is_logged = false;
$member_id = array ();
$related_buffer = false;
$banners = array ();
$banner_in_news = array ();
$xfields_in_news = array ();
$js_array = array ();
$css_array = array ();
$replace_links = array ();
$custom_news = false;
$dle_tree_comments = 0;
$attachments = array ();
$view_template = false;
$short_news_cache = false;
$onload_scripts = array();
$remove_canonical = false;
$smartphone_detected = false;
$vk_url = false;
$odnoklassniki_url = false;
$facebook_url = false;
$google_url = false;
$mailru_url = false;
$yandex_url = false;
$need_404 = false;
$xfieldsdata = "";
$xfields = array();
$custom_navigation = false;
$news_found = false;
$metatags = array ( 'title' => $config['home_title'], 'description' => $config['description'], 'keywords' => $config['keywords'], 'header_title' => "" );
$config['charset'] = strtolower(trim($config['charset']));
$_SERVER['PHP_SELF'] = htmlspecialchars( $_SERVER['PHP_SELF'], ENT_QUOTES, $config['charset'] );
