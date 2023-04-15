<?php
spl_autoload_register(function ($class_name) {

  switch ($class_name) {
    case 'DLEFiles':
    case 'thumbnail':
      include_once ENGINE_DIR . '/classes/composer/vendor/autoload.php';
      break;
  }

  switch ($class_name) {
    case 'DLESEO':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/seo.class.php'));
      break;
    case 'DLEFiles':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/filesystem.class.php'));
      break;
    case 'DLE_Comments':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/comments.class.php'));
      break;
    case 'thumbnail':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/thumb.class.php'));
      break;
    case 'dle_mail':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/mail.class.php'));
      break;
    case 'Detection\MobileDetect':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/mobiledetect.class.php'));
      break;
    case 'antivirus':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/antivirus.class.php'));
      break;
    case 'dle_template':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/templates.class.php'));
      break;
    case 'ParseFilter':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/htmlpurifier/HTMLPurifier.standalone.php'));
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/parse.class.php'));
      break;
    case 'ReCaptcha':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/recaptcha.php'));
      break;
    case 'SocialAuth':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/social.class.php'));
      break;
    case 'StopSpam':
      include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/stopspam.class.php'));
      break;
  }

});
