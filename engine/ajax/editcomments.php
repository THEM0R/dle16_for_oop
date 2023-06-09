<?php
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
 File: editcomments.php
-----------------------------------------------------
 Use: AJAX edit comments
=====================================================
*/

if(!defined('DATALIFEENGINE')) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

$area = totranslit($_REQUEST['area'], true, false);
$buffer = "";

if ( !$area) $area = "news";

$allowed_areas = array(
	
					'news' => array (
									'comments_table' => 'comments',
									),

					'ajax' => array (
									'comments_table' => 'comments',
									),

					'lastcomments' => array (
									'comments_table' => 'comments',
									),

				);

if (! is_array($allowed_areas[$area]) ) die( "error" );

if( $config['allow_comments_wysiwyg'] > 0) {

	$allowed_tags = array('div[align|style|class|data-commenttime|data-commentuser|contenteditable]', 'span[style|class|data-userurl|data-username|contenteditable]', 'p[align|style|class]', 'pre[class]', 'code', 'br', 'strong', 'em', 'ul', 'li', 'ol', 'b', 'u', 'i', 's', 'hr');
	
	if( $user_group[$member_id['user_group']]['allow_url'] ) $allowed_tags[] = 'a[href|target|style|class|title]';
	if( $user_group[$member_id['user_group']]['allow_image'] ) $allowed_tags[] = 'img[style|class|src|alt|width|height]';
	
	$parse = new ParseFilter( $allowed_tags );
	$parse->wysiwyg = true;
	
} else {
	$parse = new ParseFilter();
}

$parse->safe_mode = true;
$parse->remove_html = false;

if( !$is_logged ) { echo $lang['comm_err_1']; die();}

$id = intval( $_REQUEST['id'] );

if( !$id ) die( "error" );

$parse->allow_url = $user_group[$member_id['user_group']]['allow_url'];
$parse->allow_image = $user_group[$member_id['user_group']]['allow_image'];
$parse->allow_video = $user_group[$member_id['user_group']]['video_comments'];
$parse->allow_media = $user_group[$member_id['user_group']]['media_comments'];

if( $_REQUEST['action'] == "edit" ) {

	$dark_theme = "";

	if (defined('TEMPLATE_DIR')) {
		$template_dir = TEMPLATE_DIR;
	} else $template_dir = ROOT_DIR . "/templates/" . $config['skin'];

	if (is_file($template_dir . "/info.json")) {

		$data = json_decode(trim(file_get_contents($template_dir . "/info.json")), true);

		if (isset($data['type']) and $data['type'] == "dark") {
			$dark_theme = " dle_theme_dark";
		}
	}

	$row = $db->super_query( "SELECT id, date, autor, text, is_register FROM " . PREFIX . "_{$allowed_areas[$area]['comments_table']} where id = '$id'" );
	
	if( !$row['id'] ) die( "error" );

	$row['date'] = strtotime( $row['date'] );	
	$have_perm = 0;
	
	if( $is_logged and (($member_id['name'] == $row['autor'] AND $row['is_register'] AND $user_group[$member_id['user_group']]['allow_editc']) OR $user_group[$member_id['user_group']]['edit_allc']) ) {
		$have_perm = 1;
	}

	if ( $user_group[$member_id['user_group']]['edit_limit'] AND (($row['date'] + ($user_group[$member_id['user_group']]['edit_limit'] * 60)) < $_TIME) ) {
		$have_perm = 0;
	}
	
	if( ! $have_perm ) { echo $lang['news_info_3']; die();}

	$p_name = urlencode($row['autor']);
	$p_id = $row['id'];
	
	if( $config['allow_comments_wysiwyg'] < 1 ) {
		
		include_once (DLEPlugins::Check(ENGINE_DIR . '/ajax/bbcode.php'));
		
		$comm_txt = $parse->decodeBBCodes( $row['text'], false );
		
		if ($config['allow_comments_wysiwyg'] == 0 ) $params = "onfocus=\"setNewField(this.name, document.getElementById( 'dlemasscomments' ) )\"";
		else $params = "";
		
		$box_class = "bb-editor";

	} else {
		
		$comm_txt = $parse->decodeBBCodes( $row['text'], true, $config['allow_comments_wysiwyg'] );
		$params = "class=\"ajaxwysiwygeditor\"";
		
		$box_class = "wseditor dlecomments-editor";

		if ($config['allow_comments_wysiwyg'] == "1") {	

			if( $user_group[$member_id['user_group']]['allow_url'] ) $link_icon = "'insertLink', 'dleleech',"; else $link_icon = "";
			
			if ($user_group[$member_id['user_group']]['allow_image']) {
				if($config['bbimages_in_wysiwyg']) $link_icon .= "'dleimg',"; else $link_icon .= "'insertImage',";
			}
			
			if ($user_group[$member_id['user_group']]['allow_up_image']) {
				$link_icon .= "'dleupload',";
				$image_upload_params = "imageDefaultWidth: 0,imageUpload: true,imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif', 'bmp','webp', 'avif'],imageMaxSize: {$user_group[$member_id['user_group']]['up_image_size']} * 1024,imageUploadURL: dle_root + 'engine/ajax/controller.php?mod=upload',imageUploadParam: 'qqfile',imageUploadParams: { 'subaction' : 'upload', 'news_id' : '{$p_id}', 'area' : 'comments', 'author' : '{$p_name}', 'mode' : 'quickload', 'user_hash' : '{$dle_login_hash}' },";
			} else {
				$image_upload_params = "imageUpload: false,";
			}
			
			if ($user_group[$member_id['user_group']]['video_comments']) $link_icon .= "'insertVideo', 'dleaudio',";
			if ($user_group[$member_id['user_group']]['media_comments']) $link_icon .= "'dlemedia',";
			if ($user_group[$member_id['user_group']]['edit_allc'])  $code_icon = ",'html'"; else $code_icon = "";
		
		$bb_code = <<<HTML
<script>
	  var text_upload = "{$lang['bb_t_up']}";
	  
      $('.ajaxwysiwygeditor').froalaEditor({
        dle_root: dle_root,
        width: '100%',
        height: '220',
        language: '{$lang['wysiwyg_language']}',
        linkInsertButtons: ['linkBack'],
        dle_upload_area : "comments",
        dle_upload_user : "{$p_name}",
        dle_upload_news : "{$p_id}",

		htmlAllowedTags: ['div', 'span', 'p', 'br', 'strong', 'em', 'ul', 'li', 'ol', 'b', 'u', 'i', 's', 'a', 'img', 'hr'],
		htmlAllowedAttrs: ['class', 'href', 'alt', 'src', 'style', 'target', 'data-username', 'data-userurl', 'data-commenttime', 'data-commentuser', 'contenteditable'],
		pastePlain: true,
        imagePaste: false,
        listAdvancedTypes: false,
        {$image_upload_params}
		videoInsertButtons: ['videoBack', '|', 'videoByURL'],
		quickInsertEnabled: false,
		
        toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'formatOL', 'formatUL', '|', {$link_icon} 'emoticons', '|', 'dlehide', 'dlequote', 'dlespoiler'{$code_icon}],

        toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'formatOL', 'formatUL', '|', {$link_icon} 'emoticons', '|', 'dlehide', 'dlequote', 'dlespoiler'{$code_icon}],

        toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'formatOL', 'formatUL', '|', {$link_icon} 'emoticons', '|', 'dlehide', 'dlequote', 'dlespoiler'{$code_icon}],

        toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'formatOL', 'formatUL', '|', {$link_icon} 'emoticons', '|', 'dlehide', 'dlequote', 'dlespoiler'{$code_icon}]

      }).on('froalaEditor.image.inserted froalaEditor.image.replaced', function (e, editor, \$img, response) {

			if( response ) {
			
			    response = JSON.parse(response);
			  
			    \$img.removeAttr("data-returnbox").removeAttr("data-success").removeAttr("data-xfvalue").removeAttr("data-flink");

				if(response.flink) {
				  if(\$img.parent().hasClass("highslide")) {
		
					\$img.parent().attr('href', response.flink);
		
				  } else {
		
					\$img.wrap( '<a href="'+response.flink+'" class="highslide"></a>' );
					
				  }
				}
			  
			}
			
		});
</script>
HTML;

		} else {

			if ($user_group[$member_id['user_group']]['allow_url']) $link_icon = "link dleleech "; else $link_icon = "";
			
			$mobile_link_icon = $link_icon;
			
			if ($user_group[$member_id['user_group']]['allow_image']) {
				if($config['bbimages_in_wysiwyg']) $link_icon .= "| dleimage "; else $link_icon .= "| image ";
			}
		
			$image_upload = array();
			
			if ( $user_group[$member_id['user_group']]['allow_image'] AND  $user_group[$member_id['user_group']]['allow_up_image'] ) {
		
				$link_icon .= "dleupload ";
				$mobile_link_icon .= "dleupload ";

				$image_upload[1] = <<<HTML
var dle_image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
  var xhr, formData;

  xhr = new XMLHttpRequest();
  xhr.withCredentials = false;
  xhr.open('POST', dle_root + 'engine/ajax/controller.php?mod=upload');
  
  xhr.upload.onprogress = (e) => {
    progress(e.loaded / e.total * 100);
  };

  xhr.onload = function() {
    var json;

    if (xhr.status === 403) {
      reject('HTTP Error: ' + xhr.status, { remove: true });
      return;
    }

    if (xhr.status < 200 || xhr.status >= 300) {
      reject('HTTP Error: ' + xhr.status);
      return;
    }

    json = JSON.parse(xhr.responseText);

    if (!json || typeof json.link != 'string') {

		if(typeof json.error == 'string') {
			reject(json.error);
		} else {
			reject('Invalid JSON: ' + xhr.responseText);	
		}
		
		var editor = tinymce.activeEditor;
		var node = editor.selection.getEnd();
		editor.selection.select(node);
		editor.selection.setContent('');
		
      return;
    }

	if( json.flink ) {
		
		var editor = tinymce.activeEditor;
		var node = editor.selection.getEnd();
		editor.selection.select(node);
		editor.selection.setContent('<a href="'+json.flink+'" class="highslide"><img src="'+json.link+'" style="display: block; margin-left: auto; margin-right: auto;"></a>&nbsp;');
		editor.notificationManager.close();
		$('#mediaupload').remove();

	} else {
		resolve(json.link);
		$('#mediaupload').remove();
	}
	
  };

  xhr.onerror = function () {
    reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
  };

  formData = new FormData();
  formData.append('qqfile', blobInfo.blob(), blobInfo.filename());
  formData.append("subaction", "upload");
  formData.append("news_id", "{$p_id}");
  formData.append("area", "comments");
  formData.append("author", "{$p_name}");
  formData.append("mode", "quickload");
  formData.append("editor_mode", "tinymce");
  formData.append("user_hash", "{$dle_login_hash}");
  
  xhr.send(formData);
});
HTML;

		$image_upload[2] = <<<HTML
paste_data_images: true,
automatic_uploads: true,
images_upload_handler: dle_image_upload_handler,
images_reuse_filename: true,
image_uploadtab: false,
images_file_types: 'gif,jpg,png,jpeg,bmp,webp,avif',
file_picker_types: 'image',

file_picker_callback: function (cb, value, meta) {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');

    input.addEventListener('change', (e) => {
      const file = e.target.files[0];

		var filename = file.name;
		filename = filename.split('.').slice(0, -1).join('.');
	
      const reader = new FileReader();
      reader.addEventListener('load', () => {

        const id = filename;
        const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
        const base64 = reader.result.split(',')[1];
        const blobInfo = blobCache.create(id, file, base64);
        blobCache.add(blobInfo);

        cb(blobInfo.blobUri());

      });
      reader.readAsDataURL(file);
    });

    input.click();
},
HTML;
		
			} else {
				
				$image_upload[0] = "";
				$image_upload[1] = "";
				$image_upload[2] = "paste_data_images: false,\n";
				
			}
		
			if ($user_group[$member_id['user_group']]['video_comments']) $link_icon .= "dlemp dlaudio ";
		
			if ($user_group[$member_id['user_group']]['media_comments']) $link_icon .= "dletube ";
			
			if ($user_group[$member_id['user_group']]['edit_allc'])  $code_icon = " code"; else $code_icon = "";
			
			if( @file_exists( ROOT_DIR . '/templates/'. $config['skin'].'/editor.css' ) ) {
				
				$editor_css = "templates/{$config['skin']}/editor.css?v={$config['cache_id']}";
					
			} else $editor_css = "engine/editor/css/content.css?v={$config['cache_id']}";
	
		$bb_code = <<<HTML
<script>
var text_upload = "{$lang['bb_t_up']}";
	
setTimeout(function() {

	tinymce.remove('textarea.ajaxwysiwygeditor');

	tinyMCE.baseURL = dle_root + 'engine/editor/jscripts/tiny_mce';
	tinyMCE.suffix = '.min';

	var dle_theme = '{$dark_theme}';

	if(dle_theme != '') {
		$('body').addClass( dle_theme );
	} else {
		if ( $("body").hasClass('dle_theme_dark') ) {
			dle_theme = 'dle_theme_dark';
		}
	}

	if ( $('html').attr('class') ) {
		dle_theme = dle_theme + ' ' + $('html').attr('class');
	}

	if (typeof getBaseSize === "function") {
		var height = 260 * getBaseSize();
	} else {
		var height = 260;
	}

	{$image_upload[1]}
	
	tinymce.init({
		selector: 'textarea.ajaxwysiwygeditor',

		language : "{$lang['wysiwyg_language']}",
		element_format : 'html',
		body_class: dle_theme,
		skin: dle_theme == 'dle_theme_dark' ? 'oxide-dark' : 'oxide',
		width : "100%",
		height : height,
		deprecation_warnings: false,
		promotion: false,
		cache_suffix: '?v={$config['cache_id']}',

		plugins: "link image lists quickbars dlebutton codemirror codesample",
		
		draggable_modal: true,
		toolbar_mode: 'floating',
		contextmenu: false,
		relative_urls : false,
		convert_urls : false,
		remove_script_host : false,
		browser_spellcheck: true,
		extended_valid_elements : "div[align|style|class|data-commenttime|data-commentuser|contenteditable],span[id|data-username|data-userurl|align|style|class|contenteditable],b/strong,i/em,u,s,p[align|style|class|contenteditable],pre[class],code",
		quickbars_insert_toolbar: '',
		quickbars_selection_toolbar: 'bold italic underline | dlequote dlespoiler dlehide',
		
	    formats: {
	      bold: {inline: 'b'},
	      italic: {inline: 'i'},
	      underline: {inline: 'u', exact : true},
	      strikethrough: {inline: 's', exact : true}
	    },
		
		paste_as_text: true,
		elementpath: false,
		branding: false,
		
		menubar: false,
		link_default_target: '_blank',
		editable_class: 'contenteditable',
		noneditable_class: 'noncontenteditable',
		image_dimensions: false,
		{$image_upload[2]}
		
		toolbar: "bold italic underline | alignleft aligncenter alignright | bullist numlist | dleemo {$link_icon} | dlequote codesample dlespoiler dlehide{$code_icon}",
		
		mobile: {
			toolbar_mode: "sliding",
			toolbar: "bold italic underline | alignleft aligncenter alignright | bullist numlist | {$mobile_link_icon} dlequote dlespoiler dlehide{$code_icon}",
			
		},
		
		dle_root: dle_root,
		dle_upload_area : "comments",
		dle_upload_user : "{$p_name}",
		dle_upload_news : "{$p_id}",

		setup: (editor) => {

			const onCompeteAction = (autocompleteApi, rng, value) => {
				editor.selection.setRng(rng);
				editor.insertContent(value);
				autocompleteApi.hide();
			};

			editor.ui.registry.addAutocompleter('getusers', {
			ch: '@',
			minChars: 1,
			columns: 1,
			onAction: onCompeteAction,
			fetch: (pattern) => {

				return new Promise((resolve) => {

					$.get(dle_root + "engine/ajax/controller.php?mod=find_tags", { mode: 'users', term: pattern, skin: dle_skin, user_hash: dle_login_hash }, function(data){
						if ( data.found ) {
							resolve(data.items);
						}
					}, "json");

				});
			}
			});
		},
		
		content_css : dle_root + "{$editor_css}"

	});

}, 100);

</script>
HTML;


		}
	}
	
	$buffer = <<<HTML
<div class="comments-edit-area ignore-select">
<div class="{$box_class}{$dark_theme}">
{$bb_code}
<textarea name="dleeditcomments{$id}" id="dleeditcomments{$id}" style="width:100%;height:250px;" {$params}>{$comm_txt}</textarea>
</div>
<div class="save-buttons" style="width:100%;padding-top:5px;text-align: right;"><input class="bbcodes applychanges" title="$lang[bb_t_apply]" type="button" onclick="ajax_save_comm_edit('{$id}', '{$area}'); return false;" value="$lang[bb_b_apply]">
<input class="bbcodes cancelchanges" title="$lang[bb_t_cancel]" type="button" onclick="ajax_cancel_comm_edit('{$id}'); return false;" value="$lang[bb_b_cancel]">
</div>
</div>
HTML;

} elseif( $_REQUEST['action'] == "save" ) {
	
	if( $_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		echo $lang['sess_error']; die();
	
	}
	
	$row = $db->super_query( "SELECT id, post_id, date, autor, text, is_register, approve FROM " . PREFIX . "_{$allowed_areas[$area]['comments_table']} WHERE id = '{$id}'" );
	
	if( !$row['id'] ) die( "error" );
	
	$have_perm = 0;
	$row['date'] = strtotime( $row['date'] );
	
	if( $is_logged AND (($member_id['name'] == $row['autor'] AND $row['is_register'] AND $user_group[$member_id['user_group']]['allow_editc']) OR $user_group[$member_id['user_group']]['edit_allc'] OR $user_group[$member_id['user_group']]['admin_comments']) ) {
		$have_perm = 1;
	}

	if ( $user_group[$member_id['user_group']]['edit_limit'] AND (($row['date'] + ($user_group[$member_id['user_group']]['edit_limit'] * 60)) < $_TIME) ) {
		$have_perm = 0;
	}	

	if( !$have_perm ) { echo $lang['news_info_3']; die(); }
	
	if( $config['allow_comments_wysiwyg'] > 0) {
		
		$use_html = true;
	
	} else {
		
		if ($config['allow_comments_wysiwyg'] == "-1") $parse->allowbbcodes = false;
		
		$use_html = false;
	}
	
	$comm_txt = trim( $parse->BB_Parse( $parse->process( $_POST['comm_txt'] ), $use_html ) );
	
	if( $parse->not_allowed_tags ) {
		echo $lang['news_err_33']; die();
	}

	if( $parse->not_allowed_text ) {
		echo $lang['news_err_37']; die();
	}
	
	if( dle_strlen( $comm_txt, $config['charset'] ) > $config['comments_maxlen'] ) {
		
		echo $lang['news_err_3']; die();
	
	}
	
	if( dle_strlen($comm_txt, $config['charset']) > 65000) {
		echo $lang['news_err_3']; die();
	}
	
	if( $comm_txt == "" ) {
		
		echo $lang['news_err_11']; die();
	
	}

	if( intval($config['comments_minlen']) AND dle_strlen( $comm_txt, $config['charset'] ) < $config['comments_minlen'] ) {
	
		echo $lang['news_err_40']; die();
	
	}
	
	$comm_update = $db->safesql( $comm_txt );
	
	$db->query( "UPDATE " . PREFIX . "_{$allowed_areas[$area]['comments_table']} SET text='$comm_update', approve='1' WHERE id = '$id'" );
	
	if( !$row['approve'] ) $db->query( "UPDATE " . PREFIX . "_post SET comm_num=comm_num+1 WHERE id='{$row['post_id']}'" );
	
	$comm_txt = preg_replace ( "#\[hide(.*?)\]#i", "", $comm_txt );
	$comm_txt = str_ireplace( "[/hide]", "", $comm_txt);
	$buffer = stripslashes( $comm_txt );
	
	if( strpos ( $buffer, "dleplyrplayer" ) !== false ) {
		
		if( strpos ( $buffer, ".m3u8" ) !== false ) {
			$load_more = "\$.getCachedScript( dle_root + 'engine/classes/html5player/plyr.js?v={$config['cache_id']}');";
			$js_name = "hls.js"; 
		} else {
			$load_more = "";
			$js_name = "plyr.js"; 
		}
		
		$buffer .= <<<HTML
		<script>
			if (typeof DLEPlayer == "undefined") {
			
                $('<link>').appendTo('head').attr({type: 'text/css', rel: 'stylesheet',href: dle_root + 'engine/classes/html5player/plyr.css'});
				  
				$.getCachedScript( dle_root + 'engine/classes/html5player/{$js_name}?v={$config['cache_id']}').done(function() {
				  {$load_more} 
				});
				
			} else {
			
				var containers = document.querySelectorAll("#comm-id-{$id} .dleplyrplayer");Array.from(containers).forEach(function (container) {new DLEPlayer(container);});
				
			}
		</script>
HTML;

	}
	
	$buffer= str_replace( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $buffer );

	if( !$row['approve'] ) {
		if ( $config['allow_alt_url'] AND !$config['seo_type'] ) clear_cache( 'news_adminstats', 'full_' ); else clear_cache( 'news_adminstats', 'full_'.$row['post_id'] );
	}

	clear_cache('comm_'.$row['post_id'] );

	if ( $config['allow_subscribe'] AND !$row['approve'] ) {
		
		$name = $row['autor'];
		$post_id = $row['post_id'];

		$cat_info = get_vars( "category" );
		
		if( ! is_array( $cat_info ) ) {
			$cat_info = array ();
			
			$db->query( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );
			
			while ( $row = $db->get_row() ) {
				
				if( !$row['active'] ) continue;
				
				$cat_info[$row['id']] = array ();
				
				foreach ( $row as $key => $value ) {
					$cat_info[$row['id']][$key] = stripslashes( $value );
				}
			
			}
			set_vars( "category", $cat_info );
			$db->free();
		}

		$row = $db->super_query( "SELECT id, short_story, title, date, alt_name, category FROM ".PREFIX."_post WHERE id = '{$post_id}'" );

		$row['date'] = strtotime( $row['date'] );
		$row['category'] = intval( $row['category'] );

		if( $config['allow_alt_url'] ) {
				
			if( $config['seo_type'] == 1 OR $config['seo_type'] == 2 ) {
			
				if( $row['category'] and $config['seo_type'] == 2 ) {
					
					$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
					
				} else {
					
					$full_link = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
					
				}
				
			} else {
				
				$full_link = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
			}
			
		} else {
				
			$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
			
		}
	
		$title = stripslashes($row['title']);
		
		$row = $db->super_query( "SELECT * FROM " . PREFIX . "_email WHERE name='comments' LIMIT 0,1" );
		$mail = new dle_mail( $config, $row['use_html'] );

		if (strpos($full_link, "//") === 0) $full_link = "http:".$full_link;
		elseif (strpos($full_link, "/") === 0) $full_link = "http://".$_SERVER['HTTP_HOST'].$full_link;

		$row['template'] = stripslashes( $row['template'] );
		$row['template'] = str_replace( "{%username%}", $name, $row['template'] );
		$row['template'] = str_replace( "{%date%}", langdate( "j F Y H:i", $_TIME, true ), $row['template'] );
		$row['template'] = str_replace( "{%link%}", $full_link, $row['template'] );
		$row['template'] = str_replace( "{%title%}", $title, $row['template'] );

		$body = str_replace( '\n', "", $comm_update );
		$body = str_replace( '\r', "", $body );
			
		$body = stripslashes( stripslashes( $body ) );
		$body = str_replace( "<br />", "\n", $body );
		$body = strip_tags( $body );
			
		if( $row['use_html'] ) {
			$body = str_replace("\n", "<br />", $body );
		}
					
		$row['template'] = str_replace( "{%text%}", $body, $row['template'] );
		$row['template'] = str_replace( "{%ip%}", "--", $row['template'] );

		$db->query( "SELECT user_id, name, email, hash FROM " . PREFIX . "_subscribe WHERE news_id='{$post_id}'" );

		while($rec = $db->get_row())
		{
			if ($rec['user_id'] != $member_id['user_id'] ) {

				if (strpos($config['http_home_url'], "//") === 0) $slink = "https:".$config['http_home_url'];
				elseif (strpos($config['http_home_url'], "/") === 0) $slink = "https://".$_SERVER['HTTP_HOST'].$config['http_home_url'];
				else $slink = $config['http_home_url'];
		
				$body = str_replace( "{%username_to%}", $rec['name'], $row['template'] );
				$body = str_replace( "{%unsubscribe%}", $slink . "index.php?do=unsubscribe&post_id=" . $post_id . "&user_id=" . $rec['user_id'] . "&hash=" . $rec['hash'], $body );
				$mail->send( $rec['email'], $lang['mail_comments'], $body );

			}

		}

		$db->free();
	}
	
} else die( "error" );

$db->close();

echo $buffer;
?>