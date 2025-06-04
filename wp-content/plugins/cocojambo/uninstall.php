<?php
if (! defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}cocojambo_panel`");

delete_metadata('post', '', 'cocojambo_panel', '', true);

$wpdb->query( "DELETE p, pm
  FROM `{$wpdb->prefix}posts` p
 INNER
  JOIN `{$wpdb->prefix}postmeta` pm
    ON pm.post_id = p.ID
 WHERE p.post_type = 'book'" );

$wpdb->query( "DELETE FROM `{$wpdb->prefix}posts` WHERE post_type = 'book'" );