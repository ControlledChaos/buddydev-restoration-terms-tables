<?php

/**
 * Plugin Name: Buddydev Restoration Terms tables
 *
 */

class BuddyDev_Terms_Tables_Restoration_Helper {

	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'restore_terms_tables' ) );
	}

	public function restore_terms_tables() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = ! empty( $wpdb->charset ) ? "DEFAULT CHARACTER SET {$wpdb->charset}" : '';

		$blog_id = get_current_blog_id();

		if ( $blog_id == 1 ) {
			return;
		}

		$max_index_length = 191;

		$blog_prefix = $wpdb->get_blog_prefix( $blog_id );

		$blog_tables = "CREATE TABLE IF NOT EXISTS {$blog_prefix}terms (
						 term_id bigint(20) unsigned NOT NULL auto_increment,
						 name varchar(200) NOT NULL default '',
						 slug varchar(200) NOT NULL default '',
						 term_group bigint(10) NOT NULL default 0,
						 PRIMARY KEY  (term_id),
						 KEY slug (slug($max_index_length)),
						 KEY name (name($max_index_length))
						) $charset_collate;
						CREATE TABLE {$blog_prefix}term_taxonomy (
						 term_taxonomy_id bigint(20) unsigned NOT NULL auto_increment,
						 term_id bigint(20) unsigned NOT NULL default 0,
						 taxonomy varchar(32) NOT NULL default '',
						 description longtext NOT NULL,
						 parent bigint(20) unsigned NOT NULL default 0,
						 count bigint(20) NOT NULL default 0,
						 PRIMARY KEY  (term_taxonomy_id),
						 UNIQUE KEY term_id_taxonomy (term_id,taxonomy),
						 KEY taxonomy (taxonomy)
						) $charset_collate;";

		$tables = dbDelta( $blog_tables );

	}


}
new BuddyDev_Terms_Tables_Restoration_Helper();