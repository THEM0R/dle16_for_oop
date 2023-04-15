<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}

if ($news_name OR $newsid) {

  $allow_sql_skin = false;

  foreach ( $cat_info as $cats ) {
    if ( $cats['skin'] ) $allow_sql_skin = true;
  }

  if ($allow_sql_skin) {

    if (!$newsid) $sql_skin = $db->super_query ( "SELECT category FROM " . PREFIX . "_post where month(date) = '$month' AND year(date) = '$year' AND dayofmonth(date) = '$day' AND alt_name ='$news_name'" );
    else $sql_skin = $db->super_query ( "SELECT category FROM " . PREFIX . "_post where  id = '{$newsid}'" );

    if( isset( $sql_skin['category'] ) AND $sql_skin['category'] ) {

      $base_skin = explode ( ',', $sql_skin['category'] );

      $category_skin = $cat_info[$base_skin[0]]['skin'];

    }

    unset ( $sql_skin );
    unset ( $base_skin );

  }

}

if (isset($_GET['do']) AND $_GET['do'] == "static") {

  $name = $db->safesql( $_GET['page'] );

  $static_result = $db->super_query ( "SELECT * FROM " . PREFIX . "_static WHERE name='{$name}'" );

  if ( isset($static_result['template_folder']) AND $static_result['template_folder'] ) {

    $category_skin = $static_result['template_folder'];

  } else $category_skin = '';

}

if ($category_skin) {

  $category_skin = trim( totranslit($category_skin, false, false) );

  if ($category_skin AND @is_dir ( ROOT_DIR . '/templates/' . $category_skin )) {
    $config['skin'] = $category_skin;
  }

} elseif (isset ( $_REQUEST['action_skin_change'] )) {

  $_REQUEST['skin_name'] = trim( totranslit($_REQUEST['skin_name'], false, false) );

  if ($_REQUEST['skin_name'] AND @is_dir ( ROOT_DIR . '/templates/' . $_REQUEST['skin_name'] ) ) {
    $config['skin'] = $_REQUEST['skin_name'];
    set_cookie ( "dle_skin", $_REQUEST['skin_name'], 365 );
  }

} elseif (isset ( $_COOKIE['dle_skin'] ) ) {

  $_COOKIE['dle_skin'] = trim( totranslit($_COOKIE['dle_skin'], false, false) );

  if ($_COOKIE['dle_skin'] != '' AND @is_dir ( ROOT_DIR . '/templates/' . $_COOKIE['dle_skin'] )) {
    $config['skin'] = $_COOKIE['dle_skin'];
  }
}

if (isset ( $config["lang_" . $config['skin']] ) AND $config["lang_" . $config['skin']] != '' AND file_exists( DLEPlugins::Check(ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng') ) ) {

  include_once (DLEPlugins::Check(ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng'));

} else {

  include_once (DLEPlugins::Check(ROOT_DIR . '/language/' . $config['langs'] . '/website.lng'));

}

if (isset ( $_POST['set_new_sort'] ) AND $config['allow_change_sort']) {

  $allowed_sort = array (
    'date',
    'rating',
    'news_read',
    'comm_num',
    'title' );

  if( !$config['allow_comments'] ) unset($allowed_sort[3]);

  $find_sort = str_replace ( ".", "", totranslit ( $_POST['set_new_sort'] ) );
  $direction_sort = str_replace ( ".", "", totranslit ( $_POST['set_direction_sort'] ) );

  if (in_array($_POST['dlenewssortby'], $allowed_sort) AND stripos($find_sort, "dle_sort_") === 0) {

    if ($_POST['dledirection'] == "desc" or $_POST['dledirection'] == "asc") {

      $_SESSION[$find_sort] = $_POST['dlenewssortby'];
      $_SESSION[$direction_sort] = $_POST['dledirection'];
      $_SESSION['dle_sort_global'] = $_POST['dlenewssortby'];
      $_SESSION['dle_direction_global'] = $_POST['dledirection'];
      $_SESSION['dle_no_cache'] = "1";

    }

  }

}

$tpl = new dle_template();

if ( ($config['allow_smartphone'] AND !$_SESSION['mobile_disable'] AND $tpl->smartphone) OR $_SESSION['mobile_enable'] ) {

  if ( @is_dir ( ROOT_DIR . '/templates/smartphone' ) ) {

    $config['skin'] = "smartphone";
    $smartphone_detected = true;

  }

}

$tpl->dir = ROOT_DIR . '/templates/' . totranslit($config['skin'], false, false);

define ( 'TEMPLATE_DIR', $tpl->dir );

if ( $config['allow_registration'] ) {

  include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/sitelogin.php'));

  if ( isset($_SESSION['twofactor_auth']) AND $_SESSION['twofactor_auth'] ){

    $twofactor_alert = <<<HTML
<div id="twofactor" title="{$lang['twofactor_title']}" style="display:none;" >{$lang['twofactor_alert']}
<br><input type="text" inputmode="numeric" pattern="[0-9]*" name="dle-promt-text" id="dle-promt-text" style="width:100%;" class="ui-widget-content ui-corner-all">
<div id="twofactor_response" style="color:red"></div>
</div>
HTML;

    $onload_scripts[] = <<<HTML
$('#twofactor').dialog({
	autoOpen: true,
	show: 'fade',
	hide: 'fade',
	width: 450,
	resizable: false,
	dialogClass: "dle-popup-twofactor",
	buttons: {
		"{$lang['p_cancel']}" : function() { 
			$(this).dialog("close");						
		}, 
		"{$lang['p_enter']}": function() {
			if ( $("#dle-promt-text").val().length < 1) {
				 $("#dle-promt-text").addClass('ui-state-error');
			} else {
				var pin = $("#dle-promt-text").val();
				$.post(dle_root + "engine/ajax/controller.php?mod=twofactor", { pin: pin, skin: dle_skin }, function(data){
				
					if ( data.success ) {
					
						window.location = window.location.pathname + window.location.search;
						
					} else if (data.error) {
						
						$("#twofactor_response").html(data.errorinfo);
						$(".dle-popup-twofactor").css('max-height', '');
						$("#twofactor").css('height', 'auto');
						
					}
					
				}, "json");

			}		
		}
	}
});
HTML;

  } else {

    if ($is_logged) {

      set_cookie ( "dle_newpm", $member_id['pm_unread'], 365 );

      if( !isset($_COOKIE['dle_newpm']) ) $_COOKIE['dle_newpm'] = 0;

      if ( $member_id['pm_unread'] > intval ( $_COOKIE['dle_newpm'] ) AND !$smartphone_detected) {

        include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/pm_alert.php'));

      }

    }

  }

} else {

  $_IP = get_ip();
  $dle_login_hash = sha1( SECURE_AUTH_KEY . $_IP );

}

if (!$is_logged) $member_id['user_group'] = 5;

if ( isset( $banned_info['ip'] ) ) $blockip = check_ip ( $banned_info['ip'] );  else $blockip = false;

if ( ($is_logged AND $member_id['banned'] == "yes") OR $blockip) {

  include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/banned.php'));

}

if ( !defined('BANNERS') AND $config['allow_banner'] ) {
  include_once (DLEPlugins::Check(ENGINE_DIR . '/modules/banners.php'));
}

if( $do == "preview" ) {

  include_once (DLEPlugins::Check(ENGINE_DIR . '/preview.php'));
  die();

} elseif(isset($_GET['mod']) AND $_GET['mod'] == "print") {

  include_once (DLEPlugins::Check(ENGINE_DIR . '/print.php'));
  die();
}


$tpl->load_template( 'login.tpl' );

$tpl->set( '{login-method}', $config['auth_metod'] ? "E-Mail:" : $lang['login_metod'] );
$tpl->set( '{registration-link}', $PHP_SELF . "?do=register" );
$tpl->set( '{lostpassword-link}', $PHP_SELF . "?do=lostpassword" );
$tpl->set( '{logout-link}', $PHP_SELF . "?action=logout" );
$tpl->set( '{pm-link}', $PHP_SELF . "?do=pm" );
$tpl->set( '{group}', $user_group[$member_id['user_group']]['group_prefix'].$user_group[$member_id['user_group']]['group_name'].$user_group[$member_id['user_group']]['group_suffix'] );

if ($is_logged) {

  $tpl->set( '{login}', $member_id['name'] );
  $tpl->set( '{new-pm}', $member_id['pm_unread'] );
  $tpl->set( '{all-pm}', $member_id['pm_all'] );

  if ($member_id['favorites']) {
    $tpl->set( '{favorite-count}', count(explode("," ,$member_id['favorites'])) );
  } else $tpl->set( '{favorite-count}', '0' );

  if ( count(explode("@", $member_id['foto'])) == 2 ) {

    $tpl->set( '{foto}', 'https://www.gravatar.com/avatar/' . md5(trim($member_id['foto'])) . '?s=' . intval($user_group[$member_id['user_group']]['max_foto']) );

  } else {

    if( $member_id['foto'] ) {

      if (strpos($member_id['foto'], "//") === 0) $avatar = "http:".$member_id['foto']; else $avatar = $member_id['foto'];

      $avatar = @parse_url ( $avatar );

      if( $avatar['host'] ) {

        $tpl->set( '{foto}', $member_id['foto'] );

      } else $tpl->set( '{foto}', $config['http_home_url'] . "uploads/fotos/" . $member_id['foto'] );

      unset($avatar);

    } else $tpl->set( '{foto}', "{THEME}/dleimages/noavatar.png" );
  }

} else {

  $member_id['name'] ='';
  $tpl->set( '{login}', '' );
  $tpl->set( '{new-pm}', '0' );
  $tpl->set( '{all-pm}', '0' );
  $tpl->set( '{favorite-count}', '0' );
  $tpl->set( '{foto}', "{THEME}/dleimages/noavatar.png" );

}

if($config['allow_social'] AND $config['allow_registration']) {

  include_once(ENGINE_DIR . '/data/socialconfig.php');

  if( !isset($_SESSION['state']) OR (isset($_SESSION['state']) AND !$_SESSION['state']) ) $_SESSION['state'] = md5(uniqid(rand(), TRUE));

  if (strpos($config['http_home_url'], "//") === 0) $return_domain = "https:".$config['http_home_url'];
  elseif (strpos($config['http_home_url'], "/") === 0) $return_domain = "https://".$_SERVER['HTTP_HOST'].$config['http_home_url'];
  else  $return_domain = $config['http_home_url'];

  if ( $social_config['vk'] ) {

    $social_params = array(
      'client_id'     => $social_config['vkid'],
      'redirect_uri'  => $return_domain . "index.php?do=auth-social&provider=vk",
      'scope' => 'offline,email',
      'state' => $_SESSION['state'],
      'response_type' => 'code',
      'v'  => '5.90'
    );

    $vk_url = 'https://oauth.vk.com/authorize'.'?' . http_build_query($social_params, '', '&amp;');

    $tpl->set( '[vk]', "" );
    $tpl->set( '[/vk]', "" );
    $tpl->set( '{vk_url}', $vk_url );

  } else {

    $tpl->set_block( "'\\[vk\\](.*?)\\[/vk\\]'si", "" );
    $tpl->set( '{vk_url}', '' );
  }

  if ( $social_config['od'] ) {

    $social_params = array(
      'client_id'     => $social_config['odid'],
      'redirect_uri'  => $return_domain . "index.php?do=auth-social&provider=od",
      'scope' => 'VALUABLE_ACCESS;GET_EMAIL',
      'state' => $_SESSION['state'],
      'response_type' => 'code'
    );

    $odnoklassniki_url = 'https://connect.ok.ru/oauth/authorize'.'?' . http_build_query($social_params, '', '&amp;');

    $tpl->set( '[odnoklassniki]', "" );
    $tpl->set( '[/odnoklassniki]', "" );
    $tpl->set( '{odnoklassniki_url}', $odnoklassniki_url );

  } else {

    $tpl->set_block( "'\\[odnoklassniki\\](.*?)\\[/odnoklassniki\\]'si", "" );
    $tpl->set( '{odnoklassniki_url}', '' );
  }

  if ( $social_config['fc'] ) {

    $social_params = array(
      'client_id'     => $social_config['fcid'],
      'redirect_uri'  => $return_domain . "index.php?do=auth-social&provider=fc",
      'scope' => 'public_profile,email',
      'display' => 'popup',
      'state' => $_SESSION['state'],
      'response_type' => 'code'
    );

    $facebook_url = 'https://www.facebook.com/dialog/oauth'.'?' . http_build_query($social_params, '', '&amp;');
    $tpl->set( '[facebook]', "" );
    $tpl->set( '[/facebook]', "" );
    $tpl->set( '{facebook_url}', $facebook_url );

  } else {

    $tpl->set_block( "'\\[facebook\\](.*?)\\[/facebook\\]'si", "" );
    $tpl->set( '{facebook_url}', '' );
  }


  if ( $social_config['google'] ) {

    $social_params = array(
      'client_id'     => $social_config['googleid'],
      'redirect_uri'  => $return_domain . "index.php?do=auth-social&provider=google",
      'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
      'state' => $_SESSION['state'],
      'response_type' => 'code'
    );

    $google_url = 'https://accounts.google.com/o/oauth2/auth'.'?' . http_build_query($social_params, '', '&amp;');
    $tpl->set( '[google]', "" );
    $tpl->set( '[/google]', "" );
    $tpl->set( '{google_url}', $google_url );

  } else {

    $tpl->set_block( "'\\[google\\](.*?)\\[/google\\]'si", "" );
    $tpl->set( '{google_url}', '' );
  }

  if ( $social_config['mailru'] ) {

    $social_params = array(
      'client_id'     => $social_config['mailruid'],
      'redirect_uri'  => $return_domain . "index.php?do=auth-social&provider=mailru",
      'scope'         => 'userinfo',
      'state' => $_SESSION['state'],
      'response_type' => 'code'
    );

    $mailru_url = 'https://oauth.mail.ru/login'.'?' . http_build_query($social_params, '', '&amp;');
    $tpl->set( '[mailru]', "" );
    $tpl->set( '[/mailru]', "" );
    $tpl->set( '{mailru_url}', $mailru_url );

  } else {

    $tpl->set_block( "'\\[mailru\\](.*?)\\[/mailru\\]'si", "" );
    $tpl->set( '{mailru_url}', '' );
  }

  if ( $social_config['yandex'] ) {

    $social_params = array(
      'client_id'     => $social_config['yandexid'],
      'redirect_uri'  => $return_domain . "index.php?do=auth-social&provider=yandex",
      'state' => $_SESSION['state'],
      'response_type' => 'code'
    );

    $yandex_url = 'https://oauth.yandex.ru/authorize'.'?' . http_build_query($social_params, '', '&amp;');
    $tpl->set( '[yandex]', "" );
    $tpl->set( '[/yandex]', "" );
    $tpl->set( '{yandex_url}', $yandex_url );

  } else {

    $tpl->set_block( "'\\[yandex\\](.*?)\\[/yandex\\]'si", "" );
    $tpl->set( '{yandex_url}', '' );
  }

} else {

  $_SESSION['state'] = false;

  $tpl->set_block( "'\\[vk\\](.*?)\\[/vk\\]'si", "" );
  $tpl->set( '{vk_url}', '' );
  $tpl->set_block( "'\\[odnoklassniki\\](.*?)\\[/odnoklassniki\\]'si", "" );
  $tpl->set( '{odnoklassniki_url}', '' );
  $tpl->set_block( "'\\[facebook\\](.*?)\\[/facebook\\]'si", "" );
  $tpl->set( '{facebook_url}', '' );
  $tpl->set_block( "'\\[google\\](.*?)\\[/google\\]'si", "" );
  $tpl->set( '{google_url}', '' );
  $tpl->set_block( "'\\[mailru\\](.*?)\\[/mailru\\]'si", "" );
  $tpl->set( '{mailru_url}', '' );
  $tpl->set_block( "'\\[yandex\\](.*?)\\[/yandex\\]'si", "" );
  $tpl->set( '{yandex_url}', '' );
}

if( $user_group[$member_id['user_group']]['icon'] ) $tpl->set( '{group-icon}', "<img src=\"" . $user_group[$member_id['user_group']]['icon'] . "\" alt=\"\" />" );
else $tpl->set( '{group-icon}', "" );

if ( $user_group[$member_id['user_group']]['allow_admin'] ) {
  $tpl->set( '[admin-link]', "" );
  $tpl->set( '[/admin-link]', "" );
  $tpl->set( '{admin-link}', $config['http_home_url'] . $config['admin_path'] . "?mod=main" );
} else {
  $tpl->set( '{admin-link}', "" );
  $tpl->set_block( "'\\[admin-link\\](.*?)\\[/admin-link\\]'si", "" );
}

if ($config['allow_alt_url']) {

  $tpl->set( '{profile-link}', $config['http_home_url'] . "user/" . urlencode ( $member_id['name'] ) . "/" );
  $tpl->set( '{stats-link}', $config['http_home_url'] . "statistics.html" );
  $tpl->set( '{addnews-link}', $config['http_home_url'] . "addnews.html" );
  $tpl->set( '{favorites-link}', $config['http_home_url'] . "favorites/" );
  $tpl->set( '{newposts-link}', $config['http_home_url'] . "newposts/" );

} else {
  $tpl->set( '{profile-link}', $PHP_SELF . "?subaction=userinfo&user=" . urlencode ( $member_id['name'] ) );
  $tpl->set( '{stats-link}', $PHP_SELF . "?do=stats" );
  $tpl->set( '{addnews-link}', $PHP_SELF . "?do=addnews" );
  $tpl->set( '{favorites-link}', $PHP_SELF . "?do=favorites" );
  $tpl->set( '{newposts-link}', $PHP_SELF . "?subaction=newposts" );

}

if ($is_logged AND strpos( $tpl->copy_template, "[xfvalue_" ) !== false) {

  $xfields = xfieldsload( true );
  $xfieldsdata = xfieldsdataload( $member_id['xfields'] );

  foreach ( $xfields as $value ) {
    $preg_safe_name = preg_quote( $value[0], "'" );

    if( empty( $xfieldsdata[$value[0]] ) ) {

      $tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
      $tpl->copy_template = str_replace( "[xfnotgiven_{$value[0]}]", "", $tpl->copy_template );
      $tpl->copy_template = str_replace( "[/xfnotgiven_{$value[0]}]", "", $tpl->copy_template );

    } else {
      $tpl->copy_template = preg_replace( "'\\[xfnotgiven_{$preg_safe_name}\\](.*?)\\[/xfnotgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
      $tpl->copy_template = str_replace( "[xfgiven_{$value[0]}]", "", $tpl->copy_template );
      $tpl->copy_template = str_replace( "[/xfgiven_{$value[0]}]", "", $tpl->copy_template );
    }

    $tpl->set( "[xfvalue_{$value[0]}]", stripslashes( $xfieldsdata[$value[0]] ));

  }

} else {

  $tpl->copy_template = preg_replace( "'\\[xfgiven_(.*?)\\](.*?)\\[/xfgiven_(.*?)\\]'is", "", $tpl->copy_template );
  $tpl->copy_template = preg_replace( "'\\[xfvalue_(.*?)\\]'i", "", $tpl->copy_template );
  $tpl->copy_template = preg_replace( "'\\[xfnotgiven_(.*?)\\](.*?)\\[/xfnotgiven_(.*?)\\]'is", "", $tpl->copy_template );

}

$tpl->compile( 'login_panel' );
$tpl->clear();
