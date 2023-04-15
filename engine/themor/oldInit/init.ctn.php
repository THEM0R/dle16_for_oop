<?php

if( !defined( 'DATALIFEENGINE' ) ) {
  header( "HTTP/1.1 403 Forbidden" );
  header ( 'Location: ../' );
  die( "Hacking attempt!" );
}

//####################################################################################################################
//     Counting the number of news categories
//####################################################################################################################
if( $config['category_newscount'] ) {

  $news_count_in_array = dle_cache ( "news", "newscountcacheincats" );

  if( $news_count_in_array ) {

    $news_count_in_array = json_decode($news_count_in_array, true);

    if ( !is_array($news_count_in_array) ) $news_count_in_array = array();

  } else {

    $news_count_in_array = array();

    if( $config['no_date'] AND !$config['news_future'] ) {
      $thisdate = date( "Y-m-d H:i:s", $_TIME );
      $where_date = " AND date < '" . $thisdate . "'";
    } else $where_date = "";

    $db->query( "SELECT category, COUNT(*) AS count FROM " . PREFIX . "_post WHERE approve=1" . $where_date . " GROUP BY category" );
    $skip_parent_count = array();

    while ( $row = $db->get_row() ) {

      if(!$row['category']) continue;

      $cat_array = $temp_cat_array = explode(",", $row['category']);

      foreach ( $temp_cat_array as $value ) {

        if(!isset($news_count_in_array[$value])) $news_count_in_array[$value] = $row['count'];
        else $news_count_in_array[$value] = $news_count_in_array[$value] + $row['count'];

        $sub_count = $config['show_sub_cats'];

        if( $sub_count ) {

          $temp_parent = $cat_info[$value]['parentid'];

          while ( $temp_parent ) {

            if( !in_array($temp_parent, $cat_array) ) {

              if(!isset($news_count_in_array[$temp_parent])) $news_count_in_array[$temp_parent] = $row['count'];
              else $news_count_in_array[$temp_parent] = $news_count_in_array[$temp_parent] + $row['count'];

              $cat_array[] = $temp_parent;

              if($cat_info[$temp_parent]['show_sub'] == 2) {

                if(!isset($skip_parent_count[$temp_parent])) $skip_parent_count[$temp_parent] = $row['count'];
                else $skip_parent_count[$temp_parent] = $skip_parent_count[$temp_parent] + $row['count'];

              }

            }

            $temp_parent = $cat_info[$temp_parent]['parentid'];
          }
        }

      }

    }

    if( count( $skip_parent_count ) ) {
      foreach ( $skip_parent_count as $key => $value ) {
        $news_count_in_array[$key] = $news_count_in_array[$key] - $value;
      }
    }

    create_cache ( "news", json_encode($news_count_in_array), "newscountcacheincats" );
    unset($temp_parent, $temp_cat_array, $cat_array);
  }

  foreach ( $news_count_in_array as $key => $value ) {
    if($cat_info[$key]['id']) $cat_info[$key]['newscount'] = $value;
  }

  unset($news_count_in_array);
}

