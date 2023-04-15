<?php


if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}



if ( $config['cache_type'] ) {

  if( $config['cache_type'] == "2" ) {

    include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/redis.class.php'));

  } else {

    include_once (DLEPlugins::Check(ENGINE_DIR . '/classes/memcache.class.php'));

  }

  $dlefastcache = new dle_fastcache($config);

}


//pr3($dlefastcache);