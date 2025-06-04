<?php
require_once COCOJAMBO_PLUGIN_DIR . 'src/class-cocojambo-activate.php';
function activation_plugin() {
	Cocojambo_Activate::activate();

	cocojambo_add_post_type();
	flush_rewrite_rules();
}

function deactivation_plugin() {
	Cocojambo_Activate::deactivate();
}
function cocojambo_add_plugin_links( $links ) {
	return Cocojambo_Activate::addLinksTopluginPanel($links);
}