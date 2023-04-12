<?PHP
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
 File: engine.php
=====================================================
*/

if (!defined('DATALIFEENGINE')) {
	die ( "Hacking attempt!" );
}

if ($cstart < 0) $cstart = 0;

$CN_HALT = false;
$allow_add_comment = false;
$allow_active_news = true;
$allow_comments = false;
$allow_userinfo = false;
$active = false;
$disable_index = false;
$social_tags = array();
$canonical = false;
$url_page = false;
$user_query = false;
$news_author = false;
$attachments = array ();
$short_news_cache = false;
$sql_select_ids = false;

//pr1($_REQUEST);

// switch
require_once (ENGINE_DIR . '/themor/engine.switch.php');
/*
=====================================================
 The output of the page header
=====================================================
*/
$titl_e = '';
$nam_e = '';
$rss_url = '';
$rssturbo_url = '';
$rssdzen_url = '';
$rss_title = '';


if ($do == "cat" and $category != '' and $subaction == '') {
	
	if( isset($cat_info[$category_id]['descr']) AND $cat_info[$category_id]['descr'] ){
		
		if( !isset($_GET['cstart']) OR ( isset($_GET['cstart']) AND intval($_GET['cstart']) < 2 ) ) {
			$metatags['description'] = $cat_info[$category_id]['descr'];
		}
	}
	
	if( isset($cat_info[$category_id]['keywords']) AND $cat_info[$category_id]['keywords'] ){
		$metatags['keywords'] = $cat_info[$category_id]['keywords'];
	}	


	if (isset( $cat_info[$category_id]['metatitle'] ) AND $cat_info[$category_id]['metatitle']) {
		$metatags['header_title'] = $cat_info[$category_id]['metatitle'];
	} else {
		$nam_e = isset($cat_info[$category_id]['name']) ? stripslashes ( $cat_info[$category_id]['name'] ) : '';
	}
	
	if ($config['allow_alt_url'] ) {
		$rss_url = $url_page . "/" . "rss.xml";
		$rssturbo_url = $url_page . "/" . "rssturbo.xml";
		$rssdzen_url = $url_page . "/" . "rssdzen.xml";
	} else {
		$rss_url = $PHP_SELF . "?mod=rss&do=cat&category=" . $cat_info[$category_id]['alt_name'];
		$rssturbo_url = $PHP_SELF . "?mod=rss&rssmode=turbo&do=cat&category=" . $cat_info[$category_id]['alt_name'];
		$rssdzen_url = $PHP_SELF . "?mod=rss&rssmode=dzen&do=cat&category=" . $cat_info[$category_id]['alt_name'];

	}

} elseif ($subaction == 'userinfo') {

	$nam_e = $user;
	
	if ($config['allow_alt_url'] ) {
		$rss_url = $url_page . "/" . "rss.xml";
		$rssturbo_url = $url_page . "/" . "rssturbo.xml";
		$rssdzen_url = $url_page . "/" . "rssdzen.xml";
	} else {
		$rss_url = $PHP_SELF . "?mod=rss&subaction=allnews&user=" . urlencode ( $user );
		$rssturbo_url = $PHP_SELF . "?mod=rss&rssmode=turbo&subaction=allnews&user=" . urlencode($user);
		$rssdzen_url = $PHP_SELF . "?mod=rss&rssmode=dzen&subaction=allnews&user=" . urlencode($user);
	}

} elseif ($subaction == 'allnews') {
	$nam_e = $lang['show_user_news'] . ' ' . $user;
	
	if ($config['allow_alt_url']) {
		$rss_url = $config['http_home_url'] . "user/" . urlencode ( $user ) . "/" . "rss.xml";
		$rssturbo_url = $config['http_home_url'] . "user/" . urlencode($user) . "/" . "rssturbo.xml";
		$rssdzen_url = $config['http_home_url'] . "user/" . urlencode($user) . "/" . "rssdzen.xml";
	} else {
		$rss_url = $PHP_SELF . "?mod=rss&subaction=allnews&user=" . urlencode ( $user );
		$rssturbo_url = $PHP_SELF . "?mod=rss&rssmode=turbo&subaction=allnews&user=" . urlencode($user);
		$rssdzen_url = $PHP_SELF . "?mod=rss&rssmode=dzen&subaction=allnews&user=" . urlencode($user);
	}

} elseif ($subaction == 'newposts') $nam_e = $lang['title_new'];
elseif ($do == 'stats') $nam_e = $lang['title_stats'];
elseif ($do == 'addnews') { if( isset($_REQUEST['id']) AND intval($_REQUEST['id']) ) $nam_e = $lang['title_editnews']; else $nam_e = $lang['title_addnews']; }
elseif ($do == 'register') $nam_e = $lang['title_register'];
elseif ($do == 'favorites') $nam_e = $lang['title_fav'];
elseif ($do == 'pm') $nam_e = $lang['title_pm'];
elseif ($do == 'feedback') $nam_e = $lang['title_feed'];
elseif ($do == 'lastcomments') $nam_e = $lang['title_last'];
elseif ($do == 'lostpassword') $nam_e = $lang['title_lost'];
elseif ($do == 'search') $nam_e = $lang['title_search'];
elseif ($do == 'static' AND isset($static_descr) AND $static_descr) $titl_e = $static_descr;
elseif ($do == 'lastnews') $nam_e = $lang['last_news'];
elseif ($do == 'alltags') $nam_e = $lang['tag_cloud'];
elseif ($do == 'rules') $nam_e = $lang['rules_page'];
elseif ($do == 'tags') $nam_e = stripslashes($tag);
elseif ($do == 'xfsearch') $nam_e = $xf;
elseif ($catalog != "") { 
	$nam_e = $lang['title_catalog'] . ' &raquo; ' . $catalog;

	if ($config['allow_alt_url']) {

		$rss_url = $config['http_home_url'] . "catalog/" . urlencode ( $catalog ) . "/" . "rss.xml";
		$rssturbo_url = $config['http_home_url'] . "catalog/" . urlencode($catalog) . "/" . "rssturbo.xml";
		$rssdzen_url = $config['http_home_url'] . "catalog/" . urlencode($catalog) . "/" . "rssdzen.xml";

	} else {
		$rss_url = $PHP_SELF . "?mod=rss&catalog=" . urlencode ( $catalog );
		$rssturbo_url = $PHP_SELF . "?mod=rss&rssmode=turbo&catalog=" . urlencode($catalog);
		$rssdzen_url = $PHP_SELF . "?mod=rss&rssmode=dzen&catalog=" . urlencode($catalog);
	}

}
else {

	if ($year != '' AND $month == '' AND $day == '') $nam_e = $lang['title_date'] . ' ' . $year . ' ' . $lang['title_year'];
	if ($year != '' AND $month != '' AND $day == '') $nam_e = $lang['title_date'] . ' ' . $r[$month - 1] . ' ' . $year . ' ' . $lang['title_year1'];
	if ($year != '' AND $month != '' AND $day != '' and $subaction == '') $nam_e = $lang['title_date'] . ' ' . $day . '.' . $month . '.' . $year;
	if (($subaction OR $newsid) AND $news_found) $titl_e = $metatags['title'];

}

if ( ( isset($_GET['cstart']) AND intval($_GET['cstart']) > 1 ) OR (isset($_GET['news_page']) AND intval($_GET['news_page']) > 1) ){

	if ( isset($_GET['cstart']) AND intval($_GET['cstart']) > 1 ) $page_extra = ' &raquo; '.$lang['news_site'].' '.intval($_GET['cstart']);
	else $page_extra = ' &raquo; '.$lang['news_site'].' '.intval($_GET['news_page']);

} else $page_extra = '';



if ($nam_e) {

	$metatags['title'] = $nam_e . $page_extra . ' &raquo; ' . $metatags['title'];
	$rss_title = $metatags['title'];

} elseif ($titl_e) {

	$metatags['title'] = $titl_e . $page_extra . ' &raquo; ' . $config['home_title'];

} else $metatags['title'] .= $page_extra;

if ( $metatags['header_title'] ) $metatags['title'] = stripslashes($metatags['header_title'].$page_extra);

if ( !$rss_url ) {
	
	if ($config['allow_alt_url']) {

		$rss_url = $config['http_home_url'] . "rss.xml";
		$rssturbo_url = $config['http_home_url'] . "rssturbo.xml";
		$rssdzen_url = $config['http_home_url'] . "rssdzen.xml";

	} else {
		$rss_url = $PHP_SELF . "?mod=rss";
		$rssturbo_url = $PHP_SELF . "?mod=rss&rssmode=turbo";
		$rssdzen_url = $PHP_SELF . "?mod=rss&rssmode=dzen";
	}
	
	$rss_title = $config['home_title'];
}

if( $config['allow_own_meta'] ) {
	
	if(isset($custom_metatags['simple']) AND is_array($custom_metatags['simple']) AND count($custom_metatags['simple']) AND isset($custom_metatags['simple'][$r_uri]) AND $custom_metatags['simple'][$r_uri] ) {
		if( $custom_metatags['simple'][$r_uri]['title'] ) $metatags['title'] = $custom_metatags['simple'][$r_uri]['title'];
		if( $custom_metatags['simple'][$r_uri]['description'] ) $metatags['description'] = $custom_metatags['simple'][$r_uri]['description'];
		if( $custom_metatags['simple'][$r_uri]['keywords'] ) $metatags['keywords'] = $custom_metatags['simple'][$r_uri]['keywords'];
		if( $custom_metatags['simple'][$r_uri]['robots'] ) $metatags['robots'] = $custom_metatags['simple'][$r_uri]['robots'];
	}
	
	if(isset($custom_metatags['regex']) AND is_array($custom_metatags['regex']) AND count($custom_metatags['regex'])) {	
		foreach ($custom_metatags['regex'] as $key => $value) {
			if(preg_match($key, $r_uri)){
				if( $value['title'] ) $metatags['title'] = $value['title'];
				if( $value['description'] ) $metatags['description'] = $value['description'];
				if( $value['keywords'] ) $metatags['keywords'] = $value['keywords'];
				if( $value['robots'] ) $metatags['robots'] = $value['robots'];
		    }
		}
	}

}

$meta = DLESEO::MetaTags( array('charset' => $config['charset'] ) );

if( !isset($social_tags['image']) OR !$social_tags['image'] ) {
	$meta->twitter('card', 'summary');
}

$meta->og('type', 'article');
$meta->og('site_name', $config['home_title']);
$meta->title($metatags['title']);

if( isset($social_tags['description']) AND $social_tags['description'] ) {
	$meta->meta('description', $metatags['description'] );
} else {
	$meta->description($metatags['description']);
}

$meta->meta('keywords', $metatags['keywords']);
$meta->meta('generator', "DataLife Engine (https://dle-news.ru)");

		
if ( $disable_index ) $metatags['robots'] = "noindex,nofollow";

if ( isset($metatags['robots']) ) {
	
	$meta->robots($metatags['robots']);
	
}

if ( count($social_tags) ) {

	foreach ($social_tags as $key => $value) {

		if( $key == "news_keywords" ) {
			$meta->meta('news_keywords', $value);
		} elseif ($key == "description") {
			$meta->og('description', $value);
			$meta->twitter('description', $value);
		} elseif ($key == "image") {
			$meta->image($value);
		} elseif ($key == "url") {
			$meta->url($value);
		} else {
			$meta->og($key, $value);
		}

	}
}

if ($canonical) {
	
	if (strpos($canonical, "//") === 0) $canonical = "http:".$canonical;
	elseif (strpos($canonical, "/") === 0) $canonical = "http://".$_SERVER['HTTP_HOST'].$canonical;

	if( stripos( $canonical, 'http://' ) !== false ) {
		
		if ( isSSL() OR $config['only_ssl'] ) {
			$canonical = str_replace( "http://", "https://", $canonical );
		}
		
	}
	
	$meta->canonical($canonical);

}

if ($config['allow_rss']) {
	
	$meta->push('link', [
			'rel' => 'alternate',
			'type' => 'application/rss+xml',
			'title' => $rss_title.' RSS',
			'href' => $rss_url
		]);

}

if ($config['allow_yandex_turbo']) {

	$meta->push('link', [
		'rel' => 'alternate',
		'type' => 'application/rss+xml',
		'title' => $rss_title . ' RSS Turbo',
		'href' => $rssturbo_url
	]);

}

if ($config['allow_yandex_dzen']) {

	$meta->push('link', [
		'rel' => 'alternate',
		'type' => 'application/rss+xml',
		'title' => $rss_title . ' RSS Dzen',
		'href' => $rssdzen_url
	]);
}

$meta->push('link', [
			'rel' => 'search',
			'type' => 'application/opensearchdescription+xml',
			'title' => $config['home_title'],
			'href' => "{$PHP_SELF}?do=opensearch"
		]);

$meta->push('link', [
	'rel' => 'preconnect',
	'href' => $config['http_home_url'],
	'fetchpriority' => 'high'
]);

$metatags = (string) $meta;
unset($meta);

/*
=====================================================
 speedbar creation
=====================================================
*/

if ($config['speedbar'] AND !$view_template ) {
	
	$elements	= [];
	$position	= 1;
	
	$elements[] = array(
		'@type'		=> "ListItem",
		'position'	=> $position,
		'item'		=> array(
			'@id'	=> $config['http_home_url'],
			'name'	=> $config['short_title'],
		)
	);
	$position++;
	
	$s_navigation = "<a href=\"{$config['http_home_url']}\">" . $config['short_title'] . "</a>";

	if( $config['start_site'] == 3 AND $dle_module == "main") $titl_e = "";

	if (intval($category_id)){
		
		if($titl_e OR (isset($_GET['cstart']) AND intval($_GET['cstart']) > 1) ) {
			$last_link = true;
		} else $last_link = false;
		
		$s_navigation .= "{$config['speedbar_separator']}" . get_breadcrumbcategories ( intval($category_id), $config['speedbar_separator'], $last_link );
		
	} elseif ($do == 'tags') {
		
		$elements[] = array(
			'@type'		=> "ListItem",
			'position'	=> $position,
			'item'		=> array(
				'@id'	=> $config['http_home_url'].'tags/',
				'name'	=> $lang['tag_cloud'],
			)
		);
		$position++;
	
		if ($config['allow_alt_url']){
			
			$uri = $url_page . "/";
			$s_navigation .= "{$config['speedbar_separator']}<a href=\"{$config['http_home_url']}tags/\">{$lang['tag_cloud']}</a>";

		} else {

			$uri = $PHP_SELF."?do=tags&tag=" . $encoded_tag;
			$s_navigation .= "{$config['speedbar_separator']}<a href=\"?do=tags\">{$lang['tag_cloud']}</a>";

		}

		if ( isset($_GET['cstart']) AND intval($_GET['cstart']) > 1 ){
			$s_navigation .= $config['speedbar_separator']."<a href=\"{$uri}\">{$tag}</a>";
		} else $s_navigation .= $config['speedbar_separator'].$tag;
		
		$elements[] = array(
			'@type'		=> "ListItem",
			'position'	=> $position,
			'item'		=> array(
				'@id'	=> $uri,
				'name'	=> $tag,
			)
		);
		$position++;
	
	} elseif ($nam_e) {
		
		$s_navigation .= "{$config['speedbar_separator']}" . $nam_e;
		
		if ($canonical) {
			$elements[] = array(
				'@type'		=> "ListItem",
				'position'	=> $position,
				'item'		=> array(
					'@id'	=> $canonical,
					'name'	=> $nam_e,
				)
			);
			$position++;
		}
	}

	if ($titl_e) {
		
		$s_navigation .= "{$config['speedbar_separator']}" . $titl_e;
		
		if ($canonical) {
			$elements[] = array(
				'@type'		=> "ListItem",
				'position'	=> $position,
				'item'		=> array(
					'@id'	=> $canonical,
					'name'	=> $titl_e,
				)
			);
			$position++;
		}
		
	} else {

		if ( isset($_GET['cstart']) AND intval($_GET['cstart']) > 1 ){
		
			$page_extra = "{$config['speedbar_separator']}".$lang['news_site']." ".intval($_GET['cstart']);
			
			if ($canonical) {
				$elements[] = array(
					'@type'		=> "ListItem",
					'position'	=> $position,
					'item'		=> array(
						'@id'	=> $canonical,
						'name'	=> $lang['news_site']." ".intval($_GET['cstart']),
					)
				);
				$position++;
			}
		
		} else $page_extra = '';

		$s_navigation .= $page_extra;

	}
	
	if ( is_array($elements) AND count($elements) > 1) {
		DLESEO::AddSchema( DLESEO::Thing('BreadcrumbList', array("itemListElement" => $elements) ) );
	}
	
	$tpl->load_template ( 'speedbar.tpl' );
	$tpl->set ( '{speedbar}', stripslashes ( $s_navigation ) );
	$tpl->compile ( 'speedbar' );
	$tpl->clear ();

}
