<?php
/*
Plugin Name: MQ Permalinks
Plugin URI: http://miquado.com/
Description: Structure-Secure Permalinks 
Version: 0.1.4
Author: Adrian Kühnis
Author URI:  http://miquado.com/ 
*/

/*  Copyright 2013 Adrian Kühnis <webrequest@miquado.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * mqpermalinks plugin install script.  create htaccess
 *
 * @since 0.0.1
 * @access public
 *
 * @param void 
 * @return void
 */
function mqpermalinks_activate(){
  // Create the .htaccess file
  $rootdir = dirname(__FILE__).'/../../../';
  $home_root = parse_url(home_url());
  if(isset( $home_root['path'])){
    $home_root = trailingslashit($home_root['path']);
  }else{
    $home_root = '/';
  }

  $rules = "\n".
    '# BEGIN MQ PERMALINKS'."\n".
    '<IfModule mod_rewrite.c>'."\n".
    'RewriteEngine On'."\n".
    'RewriteBase '.$home_root."\n".
    'RewriteRule ^index\.php$ - [L]'."\n".
    'RewriteCond %{REQUEST_FILENAME} !-f'."\n".
    'RewriteCond %{REQUEST_FILENAME} !-d'."\n".
    'RewriteRule ^p([0-9]*)- index.php?p=$1&%{QUERY_STRING}'."\n".
    'RewriteRule ^a([0-9]*)- index.php?page_id=$1&%{QUERY_STRING}'."\n".
    'RewriteRule ^c([0-9]*)- index.php?cat=$1&%{QUERY_STRING}'."\n".
    '</IfModule>'."\n".
    '# END MQ PERMALINKS';
  file_put_contents($rootdir.'.htaccess',$rules);

  // Set the permalink structur to default
  update_option('permalink_structure','');
}

/**
 * the default wp permalinks menu shall be hidden because setting the 
 * permalink option would overwrite the .htaccess file
 *
 * @since 0.0.1
 * @access public
 *
 * @param void 
 * @return void
 *
*/
function mqpermalins_hide_permalinks_menu() {
  $page = remove_submenu_page( 'options-general.php', 'options-permalink.php' );
}

/**
 * mqpermalinks plugin uninstall script. 
 * Delete the .htaccess file
 *
 * @since 0.0.1
 * @access public
 *
 * @param void 
 * @return void
 */
function mqpermalinks_uninstall(){
  // remove the .htaccess file
  $rootdir = dirname(__FILE__).'/../../../';
  if(file_exists($rootdir.'.htaccess')){
    unlink($rootdir.'.htaccess');
  }
} 

/**
 * filter function for action hook post_link 
 *
 * @since 0.0.1
 * @access public
 *
 * @param $string permalink
 * @param $object post
 * @param $boolean leavename 
 * @return $string mqpermalink
 */
function mqpermalinks_post_link($permalink, $post, $leavename) {
  if(true === $leavename){
    // editable_post_name
    return get_home_url().'/p'.$post->ID.'-%postname%';
  }else{
    return get_home_url().'/p'.$post->ID.'-'.$post->post_name;
  }
}

/**
 * filter function for action hook page_link 
 *
 * @since 0.0.1
 * @access public
 *
 * @param $string permalink
 * @param $int page_id 
 * @return $string mqpermalink
 */
function mqpermalinks_page_link($permalink, $page_id,$leavename) {
  $page = get_page($page_id);
  if(true === $leavename){
    // editable_page_name
    return get_home_url().'/p'.$page_id.'-%pagename%';
  }else{
    return get_home_url().'/a'.$page_id.'-'.$page->post_name;
  }
}

/**
 * filter function for action hook category_link 
 *
 * @since 0.0.1
 * @access public
 *
 * @param $string permalink
 * @param $int category_id 
 * @return $string mqpermalink
 */
function mqpermalinks_category_link($permalink, $cat_id) {
  $cat = get_category($cat_id);
  return get_home_url().'/c'.$cat_id.'-'.$cat->slug;
}
/**
 * hides the change permalink button in the page edit form 
 *
 * @since 0.0.1
 * @access public
 *
 * @param void 
 * @return void 
 */
function mqpermalinks_disallow_permalink_button($post){
  echo '<script type="text/javascript">
    var but = document.getElementById("change-permalinks");'.
    'if(undefined != but){but.parentNode.removeChild(but);}'.
    '</script>';
}

/* 
 * Register the hooks 
*/
register_activation_hook( __FILE__, 'mqpermalinks_activate' );
register_uninstall_hook( __FILE__, 'mqpermalinks_uninstall' );
add_action( 'admin_menu', 'mqpermalins_hide_permalinks_menu', 999 );

/*
  * Add the filters and actions
*/
if (function_exists("add_action") && function_exists("add_filter")) {
  add_filter( 'post_link', 'mqpermalinks_post_link', 10, 3 );
  add_filter( 'page_link', 'mqpermalinks_page_link', 10, 3 );
  add_filter( 'category_link', 'mqpermalinks_category_link', 10, 2 );
  add_action( 'edit_page_form', 'mqpermalinks_disallow_permalink_button' );
  add_action( 'edit_form_advanced', 'mqpermalinks_disallow_permalink_button' );
}
