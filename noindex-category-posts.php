<?php

/**
 * Plugin Name: Noindex Category Posts
 * Plugin URI:  https://github.com/vestrainteractive/noindex-category-posts
 * Description: This plugin prevents posts in specific categories from being indexed by search engines using Rank Math's robots meta functionality. RANK MATH REQUIRED.  SImply add your categories to the array on line 24, activate and done.
 * Version: 1.0a
 * Author: Vestra Interactive
 * Author URI: https://vestrainteractive.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: noindex-category-posts
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Filter to set noindex to specific categories
 */
add_filter( 'rank_math/frontend/robots', function ( $robots ) {
  $categories_to_block = array('your', 'category','here'); // Replace with your category slugs
  $categories = array_map( function ( $category ) { return $category->slug; }, get_the_category() );
  if ( array_intersect( $categories_to_block, $categories ) ) {
    unset( $robots['index'] );
    $robots['noindex'] = 'noindex';
  }
  return $robots;
} );

// Plugin Activation Hook (Optional)
register_activation_hook( __FILE__, 'activate_noindex_category_posts' );

function activate_noindex_category_posts() {
  // Add any activation tasks here (e.g., flushing cache)
}

// Include the GitHub Updater class
add_action('plugins_loaded', function() {
    $file = plugin_dir_path( __FILE__ ) . 'class-github-updater.php';

    if ( file_exists( $file ) ) {
        require_once $file;
        error_log( 'GitHub Updater file included successfully.' );
    } else {
        error_log( 'GitHub Updater file not found at: ' . $file );
    }

    // Ensure the class exists before instantiating
    if ( class_exists( 'GitHub_Updater' ) ) {
        // Initialize the updater
        new GitHub_Updater( 'noindex-category-posts', 'https://github.com/vestrainteractive/noindex-category-posts', '1.0.0' ); // Replace with actual values
        error_log( 'GitHub Updater class instantiated.' );
    } else {
        error_log( 'GitHub_Updater class not found.' );
    }
});

?>
