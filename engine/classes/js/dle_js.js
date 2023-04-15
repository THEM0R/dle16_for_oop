var c_cache        = [];
var dle_poll_voted = [];
var file_uploaders = [];

function reload () {
	
	var rndval = new Date().getTime(); 
	
	document.getElementById('dle-captcha').innerHTML = '<img src="'+dle_root+'engine/modules/antibot/antibot.php?rndval=' + rndval + '" width="160" height="80" alt="" />';
	
};

function dle_change_sort(sort, direction){

  var frm = document.getElementById('news_set_sort');

  frm.dlenewssortby.value=sort;
  frm.dledirection.value=direction;

  frm.submit();
  return false;

};

function doPoll( event, news_id){

    var frm = document.getElementById('dlepollform_'+news_id);
	var dle_poll_result = frm.status.value;
	var vote_check = '';

  if (dle_poll_voted[news_id] == 1) { return; }

  if (event != 'results' && dle_poll_result != 1) {
    for (var i=0;i < frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='radio') {
            if(elmnt.checked == true){ vote_check = elmnt.value; break;}
        }
        if (elmnt.type=='checkbox') {
            if(elmnt.checked == true){ vote_check = vote_check + elmnt.value + ' ';}
        }
    }

	if (event == 'vote' && vote_check == '') { return; }

	dle_poll_voted[news_id]  = 1;

  } else { dle_poll_result = 1; frm.status.value = 1; }

  if (dle_poll_result == 1 && event == 'vote') { dle_poll_result = 0; frm.status.value = 0; event = 'list'; }

  ShowLoading('');

  $.post(dle_root + "engine/ajax/controller.php?mod=poll", { news_id: news_id, action: event, answer: vote_check, dle_skin: dle_skin, user_hash: dle_login_hash }, function(data){

		HideLoading('');

		$("#dle-poll-list-"+news_id).fadeOut(500, function() {
			$(this).html(data);
			$(this).fadeIn(500);
		});

  });

}

function IPMenu( m_ip, l1, l2, l3 ){

	var menu = [];
	
	menu[0]='<a href="https://www.nic.ru/whois/?searchWord=' + m_ip + '" target="_blank">' + l1 + '</a>';
	menu[1]='<a href="' + dle_root + dle_admin + '?mod=iptools&ip=' + m_ip + '" target="_blank">' + l2 + '</a>';
	menu[2]='<a href="' + dle_root + dle_admin + '?mod=blockip&ip=' + m_ip + '" target="_blank">' + l3 + '</a>';
	
	return menu;
};


function ajax_save_for_edit( news_id, event )
{
	var allow_br = 0;
	var news_txt = '';

	var params = {};

	if (quick_wysiwyg == "2") {

		tinyMCE.triggerSave();

	}

	$.each($('#ajaxnews'+news_id).serializeArray(), function(index,value) {

		params[value.name] = value.value;

	});

	params['id'] = news_id;
	params['field'] = event;
	params['action'] = "save";
	params['user_hash'] = dle_login_hash;

	ShowLoading('');

	$.post(dle_root + "engine/ajax/controller.php?mod=editnews", params, function(data){

		HideLoading('');

		if (data != "ok") {

			DLEalert ( data, dle_info );

		} else {

			$('#dlepopup-news-edit').dialog('close');
		    DLEconfirm( dle_save_ok, dle_confirm, function () {
				location.reload(true);
			} );

		}

	});

	return false;
};

function ajax_prep_for_edit( news_id, event )
{
	for (var i = 0, length = c_cache.length; i < length; i++) {
	    if (i in c_cache) {
			if ( c_cache[ i ] || c_cache[ i ] != '' )
			{
				ajax_cancel_comm_edit( i );
			}
	    }
	}

	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=editnews", { id: news_id, field: event, action: "edit" }, function(data){

		HideLoading('');
		var shadow = 'none';

		$('#modal-overlay').remove();

		$('body').prepend('<div id="modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #666666; opacity: .40;filter:Alpha(Opacity=40); z-index: 980; display:none;"></div>');
		$('#modal-overlay').css({'filter' : 'alpha(opacity=40)'}).fadeIn();

		var b = {};
	
		b[dle_act_lang[3]] = function() { 
			$(this).dialog('close');	
		};
	
		b[dle_act_lang[4]] = function() { 
			ajax_save_for_edit( news_id, event );			
		};
	
		$('#dlepopup-news-edit').remove();
						
		$('body').prepend("<div id='dlepopup-news-edit' class='dlepopupnewsedit' title='"+menu_short+"' style='display:none'></div>");

		$('.dlepopupnewsedit').html('');
		
		var wh = $(window).height() * 0.9;
		var ww = $(window).width() * 0.9;
		
		if(ww > 1024) { ww=1024; }
		
		$('#dlepopup-news-edit').dialog({
			autoOpen: true,
			width: ww,
			height: wh,
			buttons: b,
			resizable: false,
			dialogClass: "modalfixed dle-popup-quickedit",
			dragStart: function(event, ui) {
				shadow = $(".modalfixed").css('box-shadow');
				$(".modalfixed").css('box-shadow', 'none');
			},
			dragStop: function(event, ui) {
				$(".modalfixed").css('box-shadow', shadow);
			},
			close: function(event, ui) {
					$(this).dialog('destroy');
					$('#modal-overlay').fadeOut(function() {
			        $('#modal-overlay').remove();
			    });
			 }
		});

		if ($(window).width() > 830 && $(window).height() > 530 ) {
			$('.modalfixed.ui-dialog').css({position:"fixed"});
			$( '#dlepopup-news-edit').dialog( "option", "position", { my: "center", at: "center", of: window } );
		}
		
		$('#dlepopup-news-edit').css({overflow:"auto"});
		$('#dlepopup-news-edit').css({'overflow-x':"hidden"});
		
		$("#dlepopup-news-edit").html(data);

	}, 'html');

	return false;
};


function ajax_comm_edit( c_id, area )
{

	for (var i = 0, length = c_cache.length; i < length; i++) {
	    if (i in c_cache) {
			if ( c_cache[ i ] != '' )
			{
				ajax_cancel_comm_edit( i );
			}
	    }
	}

	if ( ! c_cache[ c_id ] || c_cache[ c_id ] == '' )
	{
		c_cache[ c_id ] = $('#comm-id-'+c_id).html();
	}

	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=editcomments", { id: c_id, area: area, action: "edit" }, function(data){

		HideLoading('');

		$('#comm-id-'+c_id).html(data);

		setTimeout(function() {
           $("html,body").stop().animate({scrollTop: $("#comm-id-" + c_id).offset().top - 100}, 700);
        }, 600);

	}, 'html');
	return false;
};

function ajax_cancel_comm_edit( c_id )
{
	if ( c_cache[ c_id ] != "" )
	{
		$("#comm-id-"+c_id).html(c_cache[ c_id ]);
	}

	c_cache[ c_id ] = '';

	return false;
};

function ajax_save_comm_edit( c_id, area )
{

	if (dle_wysiwyg == "2") {

		tinyMCE.triggerSave();

	}

	var comm_txt = $('#dleeditcomments'+c_id).val();


	ShowLoading('');

	$.post(dle_root + "engine/ajax/controller.php?mod=editcomments", { id: c_id, comm_txt: comm_txt, area: area, action: "save", user_hash: dle_login_hash }, function(data){

		HideLoading('');
		c_cache[ c_id ] = '';
		$("#comm-id-"+c_id).html(data);

	});
	return false;
};

function DeleteComments(id, hash) {

    DLEconfirm( dle_del_agree, dle_confirm, function () {

		ShowLoading('');
	
		$.get(dle_root + "engine/ajax/controller.php?mod=deletecomments", { id: id, dle_allow_hash: hash }, function(r){
	
			HideLoading('');
	
			r = parseInt(r);
		
			if (!isNaN(r)) {
				var node = null;
				
				if( dle_tree_comm == '1') { node = $("#comments-tree-item-" + r); } else { node = $("#comment-id-" + r); }

				$("html,body").stop().animate({scrollTop: node.offset().top - 70}, 700);
		
				setTimeout(function() { node.hide('blind',{},1400);}, 700);
				
			}
	
		});

	} );

};

function MarkSpam(id, hash) {

    DLEconfirm( dle_spam_agree, dle_confirm, function () {

		ShowLoading('');
	
		$.get(dle_root + "engine/ajax/controller.php?mod=adminfunction", { id: id, action: 'commentsspam', user_hash: hash }, function(data){
	
			HideLoading('');
	
			if (data != "error") {
	
			    DLEconfirm( data, dle_confirm, function () {
					location.reload(true);
				} );
	
			}
	
		});

	} );

};

function doFavorites( fav_id, event, alert )
{
	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=favorites", { fav_id: fav_id, action: event, skin: dle_skin, alert: alert, user_hash: dle_login_hash }, function(data){

		HideLoading('');
		
		if (data.success) {
			
			if( alert ) { 
				DLEalert(data.content, dle_info); 
			} else {
				$("#fav-id-" + fav_id).html(data.content);
			}
	
		} else if (data.error) {
				
			DLEalert ( data.content, dle_info );
			
		}
		
	}, "json");

	return false;
};

function CheckLogin()
{
	var name = document.getElementById('name').value;

	ShowLoading('');

	$.post(dle_root + "engine/ajax/controller.php?mod=registration", { name: name, user_hash: dle_login_hash }, function(data){

		HideLoading('');

		$("#result-registration").html(data);

	});

	return false;
};

function doCalendar(month, year, effect){

	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=calendar", { month: month, year: year }, function(data){
		HideLoading('');

		if (effect == "left" ) {

			$("#calendar-layer").hide('slide',{ direction: "left" }, 500, function(){
				$("#calendar-layer").html(data).show('slide',{ direction: "right" }, 500);
			});

		} else {

			$("#calendar-layer").hide('slide',{ direction: "right" }, 500, function(){
				$("#calendar-layer").html(data).show('slide',{ direction: "left" }, 500);
			});

		}

	});
};


function doRate( rate, id ) {
	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=rating", { go_rate: rate, news_id: id, skin: dle_skin, user_hash: dle_login_hash }, function(data){

		HideLoading('');

		if ( data.success ) {
			var rating = data.rating;

			rating = rating.replace(/&lt;/g, "<");
			rating = rating.replace(/&gt;/g, ">");
			rating = rating.replace(/&amp;/g, "&");

			$('[data-ratig-layer-id="' + id + '"]').html(rating);
			$('[data-vote-num-id="' + id + '"]').html(data.votenum);
			$('[data-likes-id="' + id + '"]').html(data.likes);
			$('[data-dislikes-id="' + id + '"]').html(data.dislike);

		} else if (data.error) {
			
			DLEalert ( data.errorinfo, dle_info );
			
		}

	}, "json");
};

function doCommentsRate( rate, id ) {
	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=ratingcomments", { go_rate: rate, c_id: id, skin: dle_skin, user_hash: dle_login_hash }, function(data){

		HideLoading('');

		if ( data.success ) {
			var rating = data.rating;

			rating = rating.replace(/&lt;/g, "<");
			rating = rating.replace(/&gt;/g, ">");
			rating = rating.replace(/&amp;/g, "&");
			
			$('[data-comments-ratig-layer-id="' + id + '"]').html(rating);
			$('[data-comments-vote-num-id="' + id + '"]').html(data.votenum);
			$('[data-comments-likes-id="' + id + '"]').html(data.likes);
			$('[data-comments-dislikes-id="' + id + '"]').html(data.dislike);

		} else if (data.error) {
			
			DLEalert ( data.errorinfo, dle_info );
			
		}

	}, "json");
};

function ajax_cancel_reply(){
	
	$('#dlefastreplycomments').hide('blind',{},1400);
	
};

function DLESendPM( name ) {
	var b = {};
	
	$('#dlesendpmpopup').remove();
	$('#dleprofilepopup').remove();

	b[dle_act_lang[3]] = function() { 
		$(this).dialog('close');
	};

	b[dle_p_send] = function() {
		
		if (dle_wysiwyg == "2") {
			tinyMCE.triggerSave();
		}
		
		var subj = 	$('#pm_subj').val();
		var comments = 	$('#pm_text').val();
		var name = 	$('#pm_name').val();
		var question_answer = $('#pm_question_answer').val();	
		var sec_code = $('#sec_code_pm').val();
		var outboxcopy = $( '#outboxcopy:checked' ).val();
		
		var g_recaptcha_response = '';
		
		if (name == '')
		{
			DLEalert ( dle_req_field[0], dle_info );
			return false;
		}
		
		if (comments == '')
		{
			DLEalert ( dle_req_field[1], dle_info );
			return false;
		}
		
		if (subj == '')
		{
			DLEalert ( dle_req_field[2], dle_info );
			return false;
		}
		
		if ( dle_captcha_type == "1" ) {

			if ( typeof grecaptcha != "undefined"  ) {
				g_recaptcha_response = grecaptcha.getResponse(recaptcha_widget);
			}

		} else if (dle_captcha_type == "2" && typeof grecaptcha != "undefined") {

			g_recaptcha_response = $('#pm-recaptcha-response').val();

			if( g_recaptcha_response == '') {

				var recaptcha_public_key = $('#pm-recaptcha-response').data('key');

				grecaptcha.execute(recaptcha_public_key, {action: 'personal_message'}).then(function(token) {
					$('#pm-recaptcha-response').val(token);
					b[dle_p_send]();
				});

				return false;

			}

		} else if (dle_captcha_type == "3") {

			if ( typeof hcaptcha != "undefined"  ) {
			   	g_recaptcha_response = hcaptcha.getResponse(recaptcha_widget);
			}

		}

		if (!outboxcopy) {
			outboxcopy = 0;
		}
		
		if (!sec_code) {
			sec_code = '';
		}
		if (!question_answer) {
			question_answer = '';
		}

		ShowLoading('');
	
		$.post(dle_root + "engine/ajax/controller.php?mod=pm", { action: 'send_pm', subj: subj, comments: comments, name: name, skin: dle_skin, sec_code: sec_code, question_answer: question_answer, g_recaptcha_response: g_recaptcha_response, outboxcopy: outboxcopy, user_hash: dle_login_hash}, function(data){
	
			HideLoading('');
			
			if ( data.success ) {
				$('#dlesendpmpopup').remove();
				DLEalert ( data.success, dle_info );
			} else if (data.error) {
				
				if (dle_captcha_type == "2") {
					if ( typeof grecaptcha != "undefined"  ) {
						var recaptcha_public_key = $('#pm-recaptcha-response').data('key');
						grecaptcha.execute(recaptcha_public_key, {action: 'pm'}).then(function(token) {
						$('#pm-recaptcha-response').val(token);
						});
					}
				}
					
				DLEalert ( data.error, dle_info );
			}
	
		}, 'json');
		
		return false;
	};
	
	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=pm", { name: name, action: 'show_send', skin: dle_skin, user_hash: dle_login_hash }, function(data){

		HideLoading('');

		$('body').append(data);
			
		$('#dlesendpmpopup').dialog({
			autoOpen: true,
			width: 800,
			resizable: false,
			dialogClass: "modalfixed dle-popup-sendpm",
			buttons: b
		});
			
		$('.modalfixed.ui-dialog').css({position:"fixed"});
		$('#dlesendpmpopup').dialog( "option", "position", { my: "center", at: "center", of: window } );
		
	}, 'html');
	
	return false;

}

function ajax_fast_reply( id, indent, needwrap){

	var editor_mode = '';

	if (dle_wysiwyg == "1" || dle_wysiwyg == "2") {

		if (dle_wysiwyg == "2") {
			tinyMCE.triggerSave();
		}

		editor_mode = 'wysiwyg';

	}
	
	var comments = 	$('#comments'+id).val();
	var name = 	$('#name'+id).val();
	var mail = 	'';
	var question_answer = $('#question_answer'+id).val();
	var sec_code = $('#sec_code'+id).val();
	var allow_subscribe = $( '#subscribe'+id+':checked' ).val();
	var postid = 	$('#postid'+id).val();
	var g_recaptcha_response = '';
	
	if (name == '')
	{
		DLEalert ( dle_req_field[0], dle_info );
		return false;
	}
	
	if (comments == '')
	{
		DLEalert ( dle_req_field[1], dle_info );
		return false;
	}

	if ( dle_captcha_type == "1" ) {

		if ( typeof grecaptcha != "undefined"  ) {
			g_recaptcha_response = grecaptcha.getResponse(recaptcha_widget);
		}

	} else if (dle_captcha_type == "2" && typeof grecaptcha != "undefined" ) {

		g_recaptcha_response = $('#comments-recaptcha-response'+id).val();

		if( g_recaptcha_response == '') {

			var recaptcha_public_key = $('#comments-recaptcha-response'+id).data('key');

			grecaptcha.execute(recaptcha_public_key, {action: 'comments'}).then(function(token) {
				$('#comments-recaptcha-response'+id).val(token);
				ajax_fast_reply( id, indent, needwrap);
			});

			return false;

		} 
	} else if (dle_captcha_type == "3") {

		if ( typeof hcaptcha != "undefined"  ) {
		   	g_recaptcha_response = hcaptcha.getResponse(recaptcha_widget);
		}

	}

	if (!allow_subscribe) {
		allow_subscribe = 0;
	}
		
	if (!sec_code) {
		sec_code = '';
	}
	
	if (!question_answer) {
		question_answer = '';
	}

	ShowLoading('');
	
	$.post(dle_root + "engine/ajax/controller.php?mod=addcomments", { post_id: postid, parent: id, indent: indent, comments: comments, name: name, mail: mail, editor_mode: editor_mode, skin: dle_skin, sec_code: sec_code, question_answer: question_answer, g_recaptcha_response: g_recaptcha_response, allow_subscribe: allow_subscribe, user_hash: dle_login_hash, needwrap: needwrap}, function(data){
	
		HideLoading('');
			
		if( data.error ) {
			
			$( data.content ).insertBefore( '#dlefastreplyesponse' );
			
		} else if ( data.success ) {

			if ($('#comm-id-' + data.id ).length) {
				
				var content = $( data.content ).find( '#comm-id-' + data.id ).html();
				
				$('#dlefastreplycomments').hide();
				
				$("html,body").stop().animate({scrollTop: $('#comm-id-' + data.id).offset().top - 100}, 600, function() {
					
					$( '#comm-id-' + data.id ).fadeOut("slow", function(){
						
						$(this).html(content + '<script>' + data.scripts + '</script>');
						
						$('#comm-id-' + data.id).fadeIn("slow");
						
					});
					
				});

			} else {
				
				$( data.content + '<script>' + data.scripts + '</script>' ).insertBefore( '#dlefastreplyesponse' );
				$('#dlefastreplycomments').hide('blind',{},500);
				
				setTimeout(function() { 
						   
					$("html,body").stop().animate({scrollTop: $("#dlefastreplyesponse").offset().top - 100}, 600, function() {
						
						$('#comments-tree-item-' + data.id ).show('blind',{},700);
						
					});
				
				}, 600);
				
			}

			
		}
	
	}, 'json');
		
	return false;
	
}

function dle_reply( id, indent, simple){
	var b = {};
	var editor_mode = '';
	var needwrap = 0;
	
	$('#dlereplypopup').remove();
	$('#dlefastreplyesponse').remove();
	$('#dlefastreplycomments').remove();
	
	if( $('#comment-id-'+id).next('.comments-tree-list').length) {
		
		$('#comment-id-'+id).next('.comments-tree-list').append("<div id='dlefastreplyesponse'></div>");
		
	} else {
		
		$( "<div id='dlefastreplyesponse'></div>" ).insertAfter( '#comment-id-'+id );
		
		needwrap = 1;
		
	}
			
	b[dle_act_lang[3]] = function() { 
		$(this).dialog('close');
	};
	
	b[dle_p_send] = function() {
		
		if (dle_wysiwyg == "1" || dle_wysiwyg == "2") {
	
			if (dle_wysiwyg == "2") {
				tinyMCE.triggerSave();
			}
	
			editor_mode = 'wysiwyg';
	
		}
		
		var comments = 	$('#comments'+id).val();
		var name = 	$('#name'+id).val();
		var mail = 	$('#mail'+id).val();
		var question_answer = $('#question_answer'+id).val();
		var sec_code = $('#sec_code'+id).val();
		var allow_subscribe = $( '#subscribe'+id+':checked' ).val();
		var postid = 	$('#postid'+id).val();
		var g_recaptcha_response = '';
		
		if (name == '')
		{
			DLEalert ( dle_req_field[0], dle_info );
			return false;
		}
		
		if (comments == '')
		{
			DLEalert ( dle_req_field[1], dle_info );
			return false;
		}
		
		if ( dle_captcha_type == "1" ) {
			if ( typeof grecaptcha != "undefined"  ) {
				g_recaptcha_response = grecaptcha.getResponse(recaptcha_widget);
			}
		} else if (dle_captcha_type == "2" && typeof grecaptcha != "undefined" ) {
			g_recaptcha_response = $('#comments-recaptcha-response'+id).val();

			if( g_recaptcha_response == '') {

				var recaptcha_public_key = $('#comments-recaptcha-response'+id).data('key');

				grecaptcha.execute(recaptcha_public_key, {action: 'comments'}).then(function(token) {
					$('#comments-recaptcha-response'+id).val(token);
					b[dle_p_send]();
				});

				return false;

			}

		} else if (dle_captcha_type == "3") {

			if ( typeof hcaptcha != "undefined"  ) {
		   		g_recaptcha_response = hcaptcha.getResponse(recaptcha_widget);
		   	}

		}

		if (!allow_subscribe) {
			allow_subscribe = 0;
		}
		
		if (!sec_code) {
			sec_code = '';
		}
		if (!question_answer) {
			question_answer = '';
		}

		ShowLoading('');
	
		$.post(dle_root + "engine/ajax/controller.php?mod=addcomments", { post_id: postid, parent: id, indent: indent, comments: comments, name: name, mail: mail, editor_mode: editor_mode, skin: dle_skin, sec_code: sec_code, question_answer: question_answer, g_recaptcha_response: g_recaptcha_response, allow_subscribe: allow_subscribe, user_hash: dle_login_hash, needwrap: needwrap}, function(data){
	
			HideLoading('');
				
			if( data.error ) {
				
				$( data.content ).insertBefore( '#dlefastreplyesponse' );
				
			} else if ( data.success ) {
				
				$('#dlereplypopup').remove();
	
				if ($('#comm-id-' + data.id ).length) {
					
					var content = $( data.content ).find( '#comm-id-' + data.id ).html();
					
					$('#dlefastreplycomments').hide();
					
					$("html,body").stop().animate({scrollTop: $('#comm-id-' + data.id).offset().top - 100}, 600, function() {
						
						$( '#comm-id-' + data.id ).fadeOut("slow", function(){
							
							$(this).html(content + '<script>' + data.scripts + '</script>');
							
							$('#comm-id-' + data.id).fadeIn("slow");
							
						});
						
					});
	
				} else {
					
					$( data.content + '<script>' + data.scripts + '</script>' ).insertBefore( '#dlefastreplyesponse' );
					$('#dlefastreplycomments').hide('blind',{},500);
					
					setTimeout(function() { 
							   
						$("html,body").stop().animate({scrollTop: $("#dlefastreplyesponse").offset().top - 100}, 600, function() {
							
							$('#comments-tree-item-' + data.id ).show('blind',{},700);
							
						});
					
					}, 600);
					
				}
				
			}
		
		}, 'json');
		
		return false;
	};
	
	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=replycomments", { id: id, indent: indent, skin: dle_skin, user_hash: dle_login_hash, needwrap: needwrap }, function(data){

		HideLoading('');

		if ( simple != '0' ) {
			
			$( "<div id='dlefastreplycomments' style='display:none'></div>" ).insertAfter( '#comment-id-'+id );

			$("html,body").stop().animate({scrollTop: $("#comment-id-"+id).offset().top + $("#comment-id-"+id).height() - 100}, 600, function() {
				
				$('#dlefastreplycomments').html(data);
				setTimeout(function() { $('#dlefastreplycomments').show('blind',{},700);}, 200);
				
			});
			
		} else {
			
			$('body').append("<div id='dlereplypopup' title='"+dle_reply_title+"' style='display:none'></div>");
			
			$('#dlereplypopup').html(data);
			
			$('#dlereplypopup').dialog({
				autoOpen: true,
				width: 800,
				resizable: false,
				dialogClass: "modalfixed dle-popup-replycomments",
				buttons: b
			});
			
			$('.modalfixed.ui-dialog').css({position:"fixed"});
			$('.dle-popup-replycomments').css({'cssText': 'width:800px; max-height: none !important'});
			$('#dlereplypopup').css({'cssText': 'height: auto !important'});
			$('#dlereplypopup').dialog( "option", "position", { my: "center", at: "center", of: window } );
		}
		
	}, 'html');
	
	return false;
};

function doAddComments(){

	var form = document.getElementById('dle-comments-form');
	var editor_mode = '';
	var question_answer = '';
	var sec_code = '';
	var g_recaptcha_response= '';
	var allow_subscribe= "0";
	var mail = '';
	
	if (dle_wysiwyg == "1" || dle_wysiwyg == "2") {

		if (dle_wysiwyg == "2") {
			tinyMCE.triggerSave();
		}

		editor_mode = 'wysiwyg';

	}
	
	if (form.name.value == '')
	{
		DLEalert ( dle_req_field[0], dle_info );
		return false;
	}
	
	if (form.comments.value == '')
	{
		DLEalert ( dle_req_field[1], dle_info );
		return false;
	}

	if ( form.question_answer ) {

	   question_answer = form.question_answer.value;

    }

	if ( form.sec_code ) {

	   sec_code = form.sec_code.value;

    }

	if ( dle_captcha_type == "1"  ) {
		if ( typeof grecaptcha != "undefined"  ) {
	   		g_recaptcha_response = grecaptcha.getResponse();
	   	}
    } else if (dle_captcha_type == "2") {

		g_recaptcha_response = $('#g-recaptcha-response').val();

	} else if (dle_captcha_type == "3") {

		if ( typeof hcaptcha != "undefined"  ) {
	   		g_recaptcha_response = hcaptcha.getResponse();
	   	}

	}

	if ( form.allow_subscribe ) {

		if ( form.allow_subscribe.checked == true ) {
	
		   allow_subscribe= "1";

		}

    }

	if ( form.mail ) {

	   mail = form.mail.value;

    }

	ShowLoading('');

	$.post(dle_root + "engine/ajax/controller.php?mod=addcomments", { post_id: form.post_id.value, comments: form.comments.value, name: form.name.value, mail: mail, editor_mode: editor_mode, skin: dle_skin, sec_code: sec_code, question_answer: question_answer, g_recaptcha_response: g_recaptcha_response, allow_subscribe: allow_subscribe, user_hash: dle_login_hash}, function(data){

		HideLoading('');

		if( data.error ) {
			
			$('#dle-ajax-comments').append(data.content);
			
		} else if ( data.success ) {
		
			if ($('#comm-id-' + data.id ).length) {
				
				var content = $( data.content ).find( '#comm-id-' + data.id ).html();
				
				$("html,body").stop().animate({scrollTop: $('#comm-id-' + data.id).offset().top - 100}, 600, function() {
					
					$( '#comm-id-' + data.id ).fadeOut("slow", function(){
						
						$(this).html(content + '<script>' + data.scripts + '</script>');
						
						$('#comm-id-' + data.id).fadeIn("slow");
						
					});
					
				});

			} else {
				
				$( data.content + '<script>' + data.scripts + '</script>').insertBefore('#dle-ajax-comments');

				if ($('#comments-tree-item-' + data.id ).length) { 
					node = $("#comments-tree-item-" + data.id); 
				} else { 
					node = $("#blind-animation-" + data.id); 
				}

				$("html,body").stop().animate({scrollTop: $('#dle-ajax-comments').offset().top - 100}, 600, function() {
					
					$(node).show('blind',{},700);
					
				});

				
			}
			
			if ( form.sec_code ) {
	           form.sec_code.value = '';
	           reload();
		    }

			if ( dle_captcha_type == "1" ) {
				if ( typeof grecaptcha != "undefined"  ) {
			   		grecaptcha.reset();
			   	}
		    } else if (dle_captcha_type == "3") {
		    	if ( typeof hcaptcha != "undefined"  ) {
					hcaptcha.reset();
				}
			}
		    
		}

	}, 'json');
	
	return false;

};

function isHistoryApiAvailable() {
    return !!(window.history && history.pushState);
};

function CommentsPage( cstart, news_id, url ) 
{
	ShowLoading('');


	$.get(dle_root + "engine/ajax/controller.php?mod=comments", { cstart: cstart, news_id: news_id, skin: dle_skin }, function(data){

		HideLoading('');

		if (!isNaN(cstart) && !isNaN(news_id)) {

			$('#dle-comm-link').off('click');

			$('#dle-comm-link').on('click', function() {
				CommentsPage( cstart, news_id );
				return false;
			});

		
		}

		scroll( 0, $("#dle-comments-list").offset().top - 100 );
	
		$("#dle-comments-list").html(data.comments); 
		$(".dle-comments-navigation").html(data.navigation); 

		if( isHistoryApiAvailable() ) {
			window.history.pushState(null, null, url);
		}


	}, "json");

	return false;
};

function dle_copy_quote(qname, time, title_text) 
{
	dle_txt= '';

	if (window.getSelection) {
		dle_txt=window.getSelection().toString();
	}
	else if (document.selection) {
		dle_txt=document.selection.createRange().text.toString();
	}

	if (dle_txt != "") {

		if (dle_wysiwyg == "0" || dle_wysiwyg == "-1" ) {

			dle_txt='[quote='+qname+']'+dle_txt+'[/quote]';

		} else {

			dle_txt = dle_txt.replace(/\n/g, '<br>');
			dle_txt = dle_txt.replace(/\r/g, '');

			if (dle_wysiwyg == "1") {
				var contenteditable = ' contenteditable="false"';
			} else {
				var contenteditable = '';
			}
			dle_txt = '<div class="quote_block noncontenteditable"><div class="title_quote" data-commenttime="' + time + '" data-commentuser="' + qname + '"' + contenteditable + '>' + title_text + '</div><div class="quote"><div class="quote_body contenteditable">' + dle_txt + '</div></div></div>';

		}

	}
};

function dle_fastreply( name, url ) 
{
	if ( !document.getElementById('dle-comments-form') ) return false;

	var input=document.getElementById('dle-comments-form').comments;
	var finalhtml = "";
	var editor_position = "";
	
	if (dle_wysiwyg == "0" || dle_wysiwyg == "-1" ) {
		
		if (dle_wysiwyg == "0") {
			input.value += "[b]"+name+"[/b], ";
		} else {
			input.value += name+", ";
		}
		
		setTimeout(function() {
		    input.focus();
		}, 800);

		editor_position = ".bb-editor";

	} else {
		editor_position = ".wseditor";
	
		if (dle_wysiwyg == "1") {

			if (url) {
				finalhtml = "<span class=\"comments-user-profile noncontenteditable fr-deletable\" data-username=\"" + encodeURI(name) + "\" data-userurl=\"" + url + "\" contenteditable=\"false\">@" + name + "</span> " + $.FE.MARKERS;
			} else {
				finalhtml = "<b>" + name + "</b>, ";
			}

			$('#comments').froalaEditor('events.focus');
			$('#comments').froalaEditor('html.insert', finalhtml, true);

		} else {

			if ( url ) {
				finalhtml = "<span class=\"comments-user-profile noncontenteditable\" data-username=\"" + encodeURI(name) + "\" data-userurl=\"" + url + "\">@" + name + "</span> ";
			} else {
				finalhtml = "<b>" + name + "</b>, ";
			}
			
			tinyMCE.execCommand('mceInsertContent', false, finalhtml);

		}
	}

	setTimeout(function() {
          $("html,body").stop().animate({scrollTop: $(editor_position).offset().top - 100}, 700);
    }, 100);
		
	return false;
};

function dle_ins( id ) 
{
	if ( !document.getElementById('dle-comments-form') ) return false;

	var input=document.getElementById('dle-comments-form').comments;
	var finalhtml = "";
	var editor_position = "";

	if( dle_txt != "" ) {

		if (dle_wysiwyg == "0" || dle_wysiwyg == "-1") {
	
			input.value += dle_txt+'\n';

			setTimeout(function() {
			    input.focus();
			}, 800);

			editor_position = ".bb-editor";

		} else {
	
			finalhtml = dle_txt;
			editor_position = ".wseditor";
	
			if (dle_wysiwyg == "1") {
				$('#comments').froalaEditor('events.focus');
				$('#comments').froalaEditor('html.insert', finalhtml + $.FE.MARKERS, true);
			} else {
				tinyMCE.execCommand('mceInsertContent', false, finalhtml + '<p><br></p>');
			}
		}

		setTimeout(function() {
	          $("html,body").stop().animate({scrollTop: $(editor_position).offset().top - 100}, 700);
	    }, 100);

	} else {

		ShowLoading('');

		$.get(dle_root + "engine/ajax/controller.php?mod=quote", { id: id, user_hash: dle_login_hash }, function(data){

			HideLoading('');

			data = data.replace(/&lt;/g, "<");
			data = data.replace(/&gt;/g, ">");
			data = data.replace(/&amp;/g, "&");
			data = data.replace(/&quot;/g, '"');
			data = data.replace(/&#039;/g, "'");
			data = data.replace(/&#039;/g, "'");
			data = data.replace(/&#34;/g, '"');

			if (dle_wysiwyg == "0" || dle_wysiwyg == "-1") {
				
				input.value += data+'\n';

				setTimeout(function() {
			          input.focus();
			    }, 800);

				editor_position = ".bb-editor";
	
			} else {
		
				finalhtml = data;
				editor_position = ".wseditor";
	
				if (dle_wysiwyg == "1") {
					$('#comments').froalaEditor('events.focus');
					$('#comments').froalaEditor('html.insert', finalhtml + $.FE.MARKERS, true);
				} else {
					tinyMCE.execCommand('mceInsertContent', false, finalhtml+'<p><br></p>');
				}
			}

			setTimeout(function() {
		          $("html,body").stop().animate({scrollTop: $(editor_position).offset().top - 100}, 700);
		    }, 100);

		});

	}


	return false;

};

function ShowOrHide( id ) {

	var item = $("#" + id);
	var image = null;

	if ( document.getElementById('image-'+ id) ) {

		image = document.getElementById('image-'+ id);

	}

	if (jQuery().lazyLoadXT) {
		$('#' + id + ' *[data-src]').lazyLoadXT();
	}

	var scrolltime = (item.height() / 200) * 1000;

	if (scrolltime > 3000 ) { scrolltime = 3000; }

	if (scrolltime < 250 ) { scrolltime = 250; }

	if (item.css("display") == "none") { 

		item.show('blind',{}, scrolltime );

		if (image) { image.src = dle_root + 'templates/'+ dle_skin + '/dleimages/spoiler-minus.gif';}

	} else {

		if (scrolltime > 2000 ) { scrolltime = 2000; }

		item.hide('blind',{}, scrolltime );
		if (image) { image.src = dle_root + 'templates/'+ dle_skin + '/dleimages/spoiler-plus.gif';}
	}

};


function ckeck_uncheck_all() {
    var frm = document.pmlist;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
};

function confirmDelete(url){

    DLEconfirm( dle_del_agree, dle_confirm, function () {
		document.location=url;
	} );
};

function setNewField(which, formname)
{
	if (which != selField)
	{
		fombj    = formname;
		selField = which;

	}
};

function dle_news_delete( id ){

		var b = {};
	
		b[dle_act_lang[1]] = function() { 
			$(this).dialog("close");						
		};

		if (allow_dle_delete_news) {

			b[dle_del_msg] = function() { 
				$(this).dialog("close");
	
				var bt = {};
						
				bt[dle_act_lang[3]] = function() { 
					$(this).dialog('close');						
				};
						
				bt[dle_p_send] = function() { 
					if ( $('#dle-promt-text').val().length < 1) {
						$('#dle-promt-text').addClass('ui-state-error');
					} else {
						var response = $('#dle-promt-text').val();
						$(this).dialog('close');
						$('#dlepopup').remove();
						$.post(dle_root + 'engine/ajax/controller.php?mod=message', { id: id, user_hash: dle_login_hash, text: response },
							function(data){
								if (data == 'ok') { document.location=dle_root + 'index.php?do=deletenews&id=' + id + '&hash=' + dle_login_hash; } else { DLEalert('Send Error', dle_info); }
						});
		
					}				
				};
						
				$('#dlepopup').remove();
						
				$('body').append("<div id='dlepopup' class='dle-promt' title='"+dle_notice+"' style='display:none'>"+dle_p_text+"<br /><br /><textarea name='dle-promt-text' id='dle-promt-text' class='ui-widget-content ui-corner-all' style='width:97%;height:100px;'></textarea></div>");
						
				$('#dlepopup').dialog({
					autoOpen: true,
					width: 500,
					resizable: false,
					dialogClass: "modalfixed dle-popup-newsdelete",
					buttons: bt
				});

				$('.modalfixed.ui-dialog').css({position:"fixed"});
				$('#dlepopup').dialog( "option", "position", { my: "center", at: "center", of: window } );
						
			};
		}
	
		b[dle_act_lang[0]] = function() { 
			$(this).dialog("close");
			document.location=dle_root + 'index.php?do=deletenews&id=' + id + '&hash=' + dle_login_hash;					
		};
	
		$("#dlepopup").remove();
	
		$("body").append("<div id='dlepopup' class='dle-promt' title='"+dle_confirm+"' style='display:none'><div id='dlepopupmessage'>"+dle_del_agree+"</div></div>");
	
		$('#dlepopup').dialog({
			autoOpen: true,
			width: 500,
			resizable: false,
			dialogClass: "modalfixed dle-popup-newsdelete",
			buttons: b
		});

		$('.modalfixed.ui-dialog').css({position:"fixed"});
		$('#dlepopup').dialog( "option", "position", { my: "center", at: "center", of: window } );


};

function MenuNewsBuild( m_id, event ){

var menu=[];

menu[0]='<a onclick="ajax_prep_for_edit(\'' + m_id + '\', \'' + event + '\'); return false;" href="#">' + menu_short + '</a>';

if (dle_admin != '') {

	menu[1]='<a href="' + dle_root + dle_admin + '?mod=editnews&action=editnews&id=' + m_id + '" target="_blank">' + menu_full + '</a>';

} else {

	menu[1]='<a href="' + dle_root + 'index.php?do=addnews&id=' + m_id + '" target="_blank">' + menu_full + '</a>';
}

if (allow_dle_delete_news) {

	menu[2]='<a onclick="sendNotice (\'' + m_id + '\'); return false;" href="#">' + dle_notice + '</a>';
	menu[3]='<a onclick="dle_news_delete (\'' + m_id + '\'); return false;" href="#">' + dle_del_news + '</a>';

}

return menu;
};

function sendNotice( id ){
	var b = {};

	b[dle_act_lang[3]] = function() { 
		$(this).dialog('close');						
	};

	b[dle_p_send] = function() { 
		if ( $('#dle-promt-text').val().length < 1) {
			$('#dle-promt-text').addClass('ui-state-error');
		} else {
			var response = $('#dle-promt-text').val();
			$(this).dialog('close');
			$('#dlepopup').remove();
			$.post(dle_root + 'engine/ajax/controller.php?mod=message', { id: id, user_hash: dle_login_hash, text: response, allowdelete: "no" },
				function(data){
					if (data == 'ok') { DLEalert(dle_p_send_ok, dle_info); }
				});

		}				
	};

	$('#dlepopup').remove();
					
	$('body').append("<div id='dlepopup' title='"+dle_notice+"' style='display:none'>"+dle_p_text+"<br /><br /><textarea name='dle-promt-text' id='dle-promt-text' class='ui-widget-content ui-corner-all' style='width:97%;height:100px;'></textarea></div>");
					
	$('#dlepopup').dialog({
		autoOpen: true,
		width: 500,
		resizable: false,
		dialogClass: "modalfixed dle-popup-sendmessage",
		buttons: b
	});

	$('.modalfixed.ui-dialog').css({position:"fixed"});
	$('#dlepopup').dialog( "option", "position", { my: "center", at: "center", of: window } );

};

function AddComplaint( id, action ){
	var b = {};
	var mailpromt = '';

	b[dle_act_lang[3]] = function() { 
		$(this).dialog('close');						
	};

	b[dle_p_send] = function() { 
		if ( $('#dle-promt-text').val().length < 1) {
			$('#dle-promt-text').addClass('ui-state-error');
		} else {
			var response = $('#dle-promt-text').val();
			var entermail = '';
			if ( $('#dle-promt-mail').val() ) {
				entermail = $('#dle-promt-mail').val();
			}

			ShowLoading('');

			$.post(dle_root + 'engine/ajax/controller.php?mod=complaint', { id: id,  text: response, action: action, mail: entermail, user_hash: dle_login_hash },
				function(data){

					HideLoading('');

					if (data == 'ok') {

						$('#dlecomplaint').remove();

						DLEalert(dle_p_send_ok, dle_info); 

					} else { DLEalert(data, dle_info); }
			});

		}				
	};

	$('#dlecomplaint').remove();

	if(dle_group == 5) {
		mailpromt = dle_mail+"<br><input type=\"text\" name=\"dle-promt-mail\" id=\"dle-promt-mail\" class=\"ui-widget-content ui-corner-all\" style=\"width:100%;\" value=\"\">";
	}
					
	$('body').append("<div id='dlecomplaint' title='"+dle_c_title+"' style='display:none'>"+dle_complaint+"<br><textarea name='dle-promt-text' id='dle-promt-text' class='ui-widget-content ui-corner-all' style='width:100%;height:140px;'></textarea>"+mailpromt+"</div>");
					
	$('#dlecomplaint').dialog({
		autoOpen: true,
		width: 600,
		resizable: false,
		dialogClass: "modalfixed dle-popup-complaint",
		buttons: b
	});

	$('.modalfixed.ui-dialog').css({position:"fixed"});
	$('#dlecomplaint').dialog( "option", "position", { my: "center", at: "center", of: window } );

};

function DLEalert(message, title){

	$("#dlepopup").remove();

	$("body").append("<div id='dlepopup' class='dle-alert' title='" + title + "' style='display:none'>"+ message +"</div>");

	$('#dlepopup').dialog({
		autoOpen: true,
		width: 470,
		resizable: false,
		dialogClass: "modalfixed dle-popup-alert",
		buttons: {
			"Ok": function() { 
				$(this).dialog("close");
				$("#dlepopup").remove();							
			} 
		}
	});

	$('.modalfixed.ui-dialog').css({position:"fixed"});
	$('#dlepopup').dialog( "option", "position", { my: "center", at: "center", of: window } );
};

function DLEconfirm(message, title, callback){

	var b = {};

	b[dle_act_lang[1]] = function() { 
					$(this).dialog("close");
					$("#dlepopup").remove();						
			    };

	b[dle_act_lang[0]] = function() { 
					$(this).dialog("close");
					$("#dlepopup").remove();
					if( callback ) callback();					
				};

	$("#dlepopup").remove();

	$("body").append("<div id='dlepopup' class='dle-confirm' title='" + title + "' style='display:none'>"+ message +"</div>");

	$('#dlepopup').dialog({
		autoOpen: true,
		width: 500,
		resizable: false,
		dialogClass: "modalfixed dle-popup-confirm",
		buttons: b
	});

	$('.modalfixed.ui-dialog').css({position:"fixed"});
	$('#dlepopup').dialog( "option", "position", { my: "center", at: "center", of: window } );
};

function DLEprompt(message, d, title, callback, allowempty){

	var b = {};

	b[dle_act_lang[3]] = function() { 
					$(this).dialog("close");						
			    };

	b[dle_act_lang[2]] = function() { 
					if ( !allowempty && $("#dle-promt-text").val().length < 1) {
						 $("#dle-promt-text").addClass('ui-state-error');
					} else {
						var response = $("#dle-promt-text").val()
						$(this).dialog("close");
						$("#dlepopup").remove();
						if( callback ) callback( response );	
					}				
				};

	$("#dlepopup").remove();

	$("body").append("<div id='dlepopup' class='dle-promt' title='" + title + "' style='display:none'>"+ message +"<br /><br /><input type='text' name='dle-promt-text' id='dle-promt-text' class='ui-widget-content ui-corner-all' style='width:97%;' value='" + d + "'/></div>");

	$('#dlepopup').dialog({
		autoOpen: true,
		width: 500,
		resizable: false,
		dialogClass: "modalfixed dle-popup-promt",
		buttons: b
	});

	$('.modalfixed.ui-dialog').css({position:"fixed"});
	$('#dlepopup').dialog( "option", "position", { my: "center", at: "center", of: window } );

	if (d.length > 0) {
		$("#dle-promt-text").select().focus();
	} else {
		$("#dle-promt-text").focus();
	}

};

var dle_user_profile = '';
var dle_user_profile_link = '';

function ShowPopupProfile( r, allowedit )
{
	var b = {};

	b[menu_profile] = function() { 
					document.location=dle_user_profile_link;						
			    };

	if (dle_group != 5) {

		b[menu_send] = function() {
			DLESendPM(dle_user_profile);
		};
	}

	if (allowedit == 1) {

		b[menu_uedit] = function() {
					$(this).dialog("close");

					var b1 = {};

					$('body').append('<div id="modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #666666; opacity: .40;filter:Alpha(Opacity=40); z-index: 980; display:none;"></div>');
					$('#modal-overlay').css({'filter' : 'alpha(opacity=40)'}).fadeIn('slow');
				
					$("#dleuserpopup").remove();
					$("body").append("<div id='dleuserpopup' title='"+menu_uedit+"' style='display:none'></div>");

					b1[dle_act_lang[3]] = function() { 
											$(this).dialog("close");
											$("#dleuserpopup").remove();
							    };

				    b1[dle_act_lang[5]] = function() {
				      window.frames.edituserframe.confirmDelete(dle_login_hash);
				    };

					b1[dle_act_lang[4]] = function() { 
						document.getElementById('edituserframe').contentWindow.document.getElementById('saveuserform').submit();							
					};
				
					$('#dleuserpopup').dialog({
						autoOpen: true,
						width: 700,
						resizable: false,
						dialogClass: "modalfixed dle-popup-userprofileadmin",
						buttons: b1,
						open: function(event, ui) {
							$("#dleuserpopup").html("<iframe name='edituserframe' id='edituserframe' width='100%' height='400' src='" + dle_root + dle_admin + "?mod=editusers&action=edituser&user=" + dle_user_profile + "&skin=" + dle_skin + "' frameborder='0' marginwidth='0' marginheight='0' allowtransparency='true'></iframe>");
						},
						beforeClose: function(event, ui) { 
							$("#dleuserpopup").html("");
						},
						close: function(event, ui) {
								$('#modal-overlay').fadeOut('slow', function() {
						        $('#modal-overlay').remove();
						    });
						 }
					});
			
					if ($(window).width() > 830 && $(window).height() > 530 ) {
						$('.modalfixed.ui-dialog').css({position:"fixed"});
						$('#dleuserpopup').dialog( "option", "position", { my: "center", at: "center", of: window } );

					}

					return false;
					
			    };

	}

	$("#dleprofilepopup").remove();

	$("body").append(r);

	$('#dleprofilepopup').dialog({
		autoOpen: true,
		resizable: false,
		dialogClass: "dle-popup-userprofile",
		buttons: b,
		width: 550
	});

	return false;
};

function ShowProfile( name, url, allowedit )
{

	if (dle_user_profile == name && document.getElementById('dleprofilepopup')) {$('#dleprofilepopup').dialog('open');return false;}

	dle_user_profile = name;
	dle_user_profile_link = url;

	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=profile", { name: name, skin: dle_skin, user_hash: dle_login_hash }, function(data){

		HideLoading('');

		ShowPopupProfile( data, allowedit );

	});

	
	return false;
};

function FastSearch()
{
	$('#story').attr('autocomplete', 'off');
	
	$('#story').blur(function(){
		 	$('#searchsuggestions').fadeOut();
	});

	$('#story').keyup(function() {
		var inputString = $(this).val();

		if(inputString.length == 0) {
			$('#searchsuggestions').fadeOut();
		} else {

			if (dle_search_value != inputString && inputString.length >= dle_min_search) {
				clearInterval(dle_search_delay);
				dle_search_delay = setInterval(function() { dle_do_search(inputString); }, 600);
			}

		}
	
	});
};

function dle_do_search( inputString )
{
	clearInterval(dle_search_delay);

	$('#searchsuggestions').remove();

	$("body").append("<div id='searchsuggestions' style='display:none'></div>");

	$.post(dle_root + "engine/ajax/controller.php?mod=search", {query: ""+inputString+"", user_hash: dle_login_hash}, function(data) {
			$('#searchsuggestions').html(data).fadeIn().css({'position' : 'absolute', top:0, left:0}).position({
				my: "left top",
				at: "left bottom",
				of: "#story",
				collision: "fit flip"
			});
		});

	dle_search_value = inputString;

};

function ShowLoading( message, positionx,  positiony) {

	var classname = '';

	if (typeof positionx == 'undefined') {
		var positionx = 'center';
	}

	if (typeof positiony == 'undefined') {
		var positiony = 'center';
	}

	if (typeof message == 'undefined') {
		var positiony = '';
	}

	$('#loading-layer').remove();

	if ( message.length === 0 || !message.trim() ) {
		message = '<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="32px" height="32px" viewBox="0 0 128 128" xml:space="preserve"><g><path fill="#ffffff" d="M64,128a64,64,0,1,1,64-64A64,64,0,0,1,64,128ZM64,2.75A61.25,61.25,0,1,0,125.25,64,61.25,61.25,0,0,0,64,2.75Z"/><path fill="#ffffff" d="M64 128a64 64 0 1 1 64-64 64 64 0 0 1-64 64zM64 2.75A61.2 61.2 0 0 0 3.34 72.4c1.28-3.52 3.9-6.32 7.5-6.86 6.55-1 11.9 2.63 13.6 8.08 3.52 11.27.5 23 15 35.25 19.47 16.46 40.34 13.54 52.84 9.46A61.25 61.25 0 0 0 64 2.75z"/><animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1400ms" repeatCount="indefinite"></animateTransform></g></svg>';
		classname ='withouttext';
	} else {
		classname ='withtext';
		message = '<span>' + message + '</span>';
	}

	$('body').append('<div id="loading-layer" class="' + classname + '" style="display:none">' + message + '</div>');

	var setX = ( $(window).width()  - $("#loading-layer").outerWidth()  ) / 2;
	var setY = ( $(window).height() - $("#loading-layer").outerHeight() ) / 2;

	switch (positionx) {
	  case 'left':
	    setX = 15;
	    break;
	  case 'right':
	    setX = ( $(window).width()  - $("#loading-layer").outerWidth()  ) - 15;
	    break;
	}

	switch (positiony) {
	  case 'top':
	    setY = 15;
	    break;
	  case 'bottom':
	    setY = ( $(window).height() - $("#loading-layer").outerHeight() ) - 15;
	    break;
	}

	$("#loading-layer").css( {
		left : setX + "px",
		top : setY + "px",
		position : 'fixed',
		zIndex : '9999'
	});
		
	$("#loading-layer").fadeTo('slow', 1);

};

function HideLoading( message )
{
	$("#loading-layer").fadeOut('slow', function() {
		$('#loading-layer').remove();
  		}
	);
};

function ShowAllVotes( )
{
	if (document.getElementById('dlevotespopup')) {$('#dlevotespopup').dialog('open');return false;}

	$.ajaxSetup({
	  cache: false
	});

	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=allvotes&dle_skin=" + dle_skin, function(data){

		HideLoading('');
		$("#dlevotespopup").remove();	
	
		$("body").append( data );

		$(".dlevotebutton").button();

			$('#dlevotespopup').dialog({
				autoOpen: true,
				resizable: false,
				dialogClass: "dle-popup-allvotes",
				width: 600
			});

			if ($('#dlevotespopupcontent').height() > 400 ) {

				$('#dlevotespopupcontent').height(400);
				$('#dlevotespopup').dialog( "option", "height", $('#dlevotespopupcontent').height() + 60 );
				$('#dlevotespopup').dialog( "option", "position", 'center' );
			} else {

				$('#dlevotespopup').dialog( "option", "height", $('#dlevotespopupcontent').height() + 60 );
				$('#dlevotespopup').dialog( "option", "position", 'center' );

			}

	 });

	return false;
};

function fast_vote( vote_id )
{
	var vote_check = $('#vote_' + vote_id + ' input:radio[name=vote_check]:checked').val();
	
	if (typeof vote_check == "undefined") {
		return false;
	}
	
	ShowLoading('');

	$.get(dle_root + "engine/ajax/controller.php?mod=vote", { vote_id: vote_id, vote_action: "vote", vote_mode: "fast_vote", vote_check: vote_check, dle_skin: dle_skin, user_hash: dle_login_hash }, function(data){

		HideLoading('');

		$("#dle-vote_list-" + vote_id).fadeOut(500, function() {
			$(this).html(data);
			$(this).fadeIn(500);
		});

	});

	return false;
};

function AddIgnorePM( id, text ){

    DLEconfirm( text, dle_confirm, function () {

		ShowLoading('');
	
		$.get(dle_root + "engine/ajax/controller.php?mod=adminfunction", { id: id, action: "add_ignore", skin: dle_skin, user_hash: dle_login_hash }, function(data){
	
			HideLoading('');
	
			DLEalert ( data, dle_info );
			return false;
		
	
		});

	} );
};

function DelIgnorePM( id, text ){

    DLEconfirm( text, dle_confirm, function () {

		ShowLoading('');
	
		$.get(dle_root + "engine/ajax/controller.php?mod=adminfunction", { id: id, action: "del_ignore", skin: dle_skin, user_hash: dle_login_hash }, function(data){
	
			HideLoading('');
	
			$("#dle-ignore-list-" + id).html('');
			DLEalert ( data, dle_info );
			return false;
		
	
		});

	} );
	
	return false;
};

function DelSocial( id, text ){

    DLEconfirm( text, dle_confirm, function () {

		ShowLoading('');
	
		$.get(dle_root + "engine/ajax/controller.php?mod=adminfunction", { id: id, action: "del_social", user_hash: dle_login_hash }, function(data){
	
			HideLoading('');
	
			$("#dle-social-list-" + id).html('');
			DLEalert ( data, dle_info );
			return false;
		
	
		});

	} );
	
	return false;
};

function subscribe( id, sub_action ){

	var text = dle_sub_agree;
	
	if( sub_action == 0 ) {
		text = dle_unsub_agree;
	} 
		
	DLEconfirm( text, dle_confirm, function () {	
		ShowLoading('');
		
		$.get(dle_root + "engine/ajax/controller.php?mod=commentssubscribe", { news_id: id, skin: dle_skin, sub_action: sub_action, user_hash: dle_login_hash }, function(data){
			
			HideLoading('');
			
			if ( data.success ) {
				DLEalert ( data.info, dle_info );
			} else if (data.error) {
				DLEalert ( data.errorinfo, dle_info );
			}
			
		}, "json");
	} );
	
	return false;
};

var media_upload_manager = false;

function media_upload ( area, author, news_id, wysiwyg){

		var shadow = 'none';

		var manager = area+author+news_id+wysiwyg;

		if ($("#mediaupload").hasClass('ui-dialog-content') && media_upload_manager == manager ) {
			$('#mediaupload').dialog('open');
			check_all();
			return false;
		}

		$('#mediaupload').remove();
		$('body').append("<div id='mediaupload' class='mediaupload-body' title='"+text_upload+"' style='display:none'></div>");

		ShowLoading('');

		$.get(dle_root+"engine/ajax/controller.php", { mod: 'upload', area: area, news_id: news_id, author: author, wysiwyg: wysiwyg }, function(data){

			HideLoading('');

			$("#mediaupload").html(data);

			var wh = $(window).height() * 0.9;
			var ww = $(window).width() * 0.9;
			
			if(ww > 830) { ww=830; }
			if(wh > 600) { wh=600; }

			$('#mediaupload').dialog({
				autoOpen: true,
				width: ww,
				height: wh,
				resizable: false,
				dialogClass: "modalfixed dle-popup-mediaupload",
				open: function(event, ui) { 
						$('.dle-popup-mediaupload').append( $('#mediaupload-buttonpane').html() );
						$('#mediaupload-buttonpane').remove();
				},
				dragStart: function(event, ui) {
					shadow = $(".modalfixed").css('box-shadow');
					$(".modalfixed").fadeTo(0, 0.7).css('box-shadow', 'none');
					$("#mediaupload").css('visibility', 'hidden');
				},
				dragStop: function(event, ui) {
					$(".modalfixed").fadeTo(0, 1).css('box-shadow', shadow);
					$("#mediaupload").css('visibility', 'visible');
				}
			});
			
			media_upload_manager = manager;

			if ($(window).width() > 830 && $(window).height() > 530 ) {
				$('.modalfixed.ui-dialog').css({position:"fixed"});
				$('#mediaupload').dialog( "option", "position", { my: "center", at: "center", of: window } );
			}

		}, 'html');

		return false;

};

function dropdownmenu(obj, e, menucontents, menuwidth){

	if (window.event) event.cancelBubble=true;
	else if (e.stopPropagation) e.stopPropagation();

	var menudiv = $('#dropmenudiv');

	if (menudiv.is(':visible')) { clearhidemenu(); menudiv.fadeOut('fast'); return false; }

	menudiv.remove();

	$('body').append('<div id="dropmenudiv" style="display:none;position:absolute;z-index:100;width:165px;"></div>');

	menudiv = $('#dropmenudiv');

	menudiv.html(menucontents.join(""));

	if (menuwidth) menudiv.width(menuwidth);

	var windowx = $(document).width() - 30;
	var offset = $(obj).offset();

	if (windowx-offset.left < menudiv.width())
			offset.left = offset.left - (menudiv.width()-$(obj).width());

	menudiv.css( {
		left : offset.left + "px",
		top : offset.top+$(obj).height()+"px"
	});

	menudiv.fadeTo('fast', 0.9);

	menudiv.mouseenter(function(){
	      clearhidemenu();
	    }).mouseleave(function(){
	      delayhidemenu();
	});

	$(document).one("click", function() {
		hidemenu();
	});

	return false;
};

function setcookie(cname, cvalue) {
  var d = new Date();
  d.setTime(d.getTime() + (31 * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
};

function get_local_storage(key) {

	try {
		if( localStorage.getItem(key) ) {
			return JSON.parse(localStorage.getItem(key));
		} else {
			return null;
		}
    } catch (e) {
        return null;
	}
};

function set_local_storage(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {}
};

function del_local_storage(key, value) {
    try {
        localStorage.removeItem(key);
    } catch (e) {}
};


function save_last_viewed(id) {

	id = parseInt(id);
		
	if (isNaN(id)) {
		return null;
	}

	var id_array = get_local_storage('viewed_ids');

	if( $.isArray( id_array ) ) {

		if( $.inArray( id, id_array ) == -1 ) {

			if(id_array.length > 19 ) {
				id_array.pop();
			}
				
			id_array.unshift(id);
				
		}
			
	} else {
			
		id_array = [];
		id_array.push(id);
			
	}
		
	set_local_storage('viewed_ids', id_array);
	setcookie('viewed_ids', id_array.join());

	return true;
		
};

function hidemenu(e){
	$("#dropmenudiv").fadeOut("fast");
};

function delayhidemenu(){
	delayhide=setTimeout("hidemenu()",1000);
};

function clearhidemenu(){

	if (typeof delayhide!="undefined")
		clearTimeout(delayhide);
};

jQuery(function($){
	
		var hsloaded = false;
		var dlebannerids = new Array();
		var mailpromt = '';
		
		$(document).keydown(function(event){
		    if (event.which == 13 && event.ctrlKey) {
		    	
		    	event.preventDefault();

				if (window.getSelection) {
					var selectedText = window.getSelection();
				}
				else if (document.getSelection) {
					var selectedText = document.getSelection();
				}
				else if (document.selection) {
					var selectedText = document.selection.createRange().text;
				}

				if (selectedText == "" ) { return false; }

				if (selectedText.toString().length > 255 ) { DLEalert(dle_big_text, dle_info); return false;}

				var b = {};
			
				b[dle_act_lang[3]] = function() { 
					$(this).dialog('close');						
				};
			
				b[dle_p_send] = function() { 
					if ( $('#dle-promt-text').val().length < 1) {
						$('#dle-promt-text').addClass('ui-state-error');
					} else {
						var response = $('#dle-promt-text').val();
						var selectedText = $('#orfom').text();
						var entermail = '';
						if ( $('#dle-promt-mail').val() ) {
							entermail = $('#dle-promt-mail').val();
						}

						ShowLoading('');

						$.post(dle_root + 'engine/ajax/controller.php?mod=complaint', { seltext: selectedText,  text: response, mail: entermail, user_hash: dle_login_hash, action: "orfo", url: window.location.href },
							function(data){
								
								HideLoading('');

								if (data == 'ok') {

									$('#dlecomplaint').remove();
									DLEalert(dle_p_send_ok, dle_info); 

								} else { DLEalert(data, dle_info); }

							});
			
					}				
				};
			
				$('#dlecomplaint').remove();

				if(dle_group == 5) {
					mailpromt = dle_mail+"<br><input type=\"text\" name=\"dle-promt-mail\" id=\"dle-promt-mail\" class=\"ui-widget-content ui-corner-all\" style=\"width:100%;\" value=\"\">";
				}
								
				$('body').append("<div id='dlecomplaint' class='dle-promt' title='"+dle_c_title+"' style='display:none'>"+dle_orfo_title+"<br><textarea name='dle-promt-text' id='dle-promt-text' class='ui-widget-content ui-corner-all' style='width:100%;height:140px;'></textarea>"+mailpromt+"<div id='orfom' style='display:none'>"+selectedText+"</div></div>");
								
				$('#dlecomplaint').dialog({
					autoOpen: true,
					width: 600,
					resizable: false,
					dialogClass: "modalfixed dle-popup-complaint",
					buttons: b
				});
			
				$('.modalfixed.ui-dialog').css({position:"fixed"});
				$('#dlecomplaint').dialog( "option", "position", { my: "center", at: "center", of: window } );
				
		    };
			
		});
		
		setTimeout(function() {
			$("img[data-maxwidth]").each(function(){
				var width = $(this).width();
				var maxwidth =  $(this).data('maxwidth');

				if( $(this)[0].naturalWidth ) {
					width = $(this)[0].naturalWidth;
				}
				
				if (width > maxwidth) {
					$(this).width(maxwidth);
					
					$(this).wrap('<a href="' + $(this).attr('src') +'" data-highslide="single" target="_blank"></a>' );
					
					if (typeof Fancybox == "undefined" && hsloaded == false ) {

						hsloaded = true;
						$.getCachedScript(dle_root + 'engine/classes/fancybox/fancybox.js');
						
					}
				}
			});
		}, 300);
		
		setTimeout(function() {
			$("div[data-dlebclicks]").each(function(){
				var id = $(this).data('dlebid');

				$(this).find('a').on('click', function() {
					$.post(dle_root + "engine/ajax/controller.php?mod=adminfunction", { 'id': id, action: 'bannersclick', user_hash: dle_login_hash });
				});

			});
		}, 400);

		$("div[data-dlebviews]").each(function(){
			dlebannerids.push($(this).data('dlebid'));
		});

		if(dlebannerids.length)	{
			setTimeout(function() {
				$.post(dle_root + "engine/ajax/controller.php?mod=adminfunction", { 'ids[]': dlebannerids, action: 'bannersviews', user_hash: dle_login_hash });
	        }, 1000);
		}

		$(document).on("click", '.comments-user-profile', function (e) {

			if ($(this).data('userurl') && $(this).data('username')) {
				ShowProfile($(this).data('username'), $(this).data('userurl'), 0);
			}

			return false;
		});

});

jQuery.getCachedScript = function( url, options ) {
 
  options = $.extend( options || {}, {
    dataType: "script",
    cache: true,
    url: url
  });
 
  return jQuery.ajax( options );
};
