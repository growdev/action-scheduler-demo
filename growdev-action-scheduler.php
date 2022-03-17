<?php
/**
 * Plugin Name:     Grow Development - Action Scheduler Demo
 * Plugin URI:      https://growdevelopment.com
 * Description:     Demonstrate Action Scheduler
 * Author:          Grow Development
 * Author URI:      https://growdevelopment.com
 * Text Domain:     growdev
 * Domain Path:     /languages
 * Version:         1.0.0
 */

namespace GrowDevelopment\Demo;

add_action( 'admin_menu', __NAMESPACE__ . '\add_menu' );
function add_menu() {
	add_menu_page(
		'Grow Development',
		'Grow Development',
		'manage_options',
		'growdev-demo',
		__NAMESPACE__ . '\admin_page',
		'dashicons-admin-tools',
		99
	);
}

function admin_page() {
	?>
	<h1>Action Scheduler Demo</h1>
	<br/><br/>
	<ul>
	<?php
	if ( isset( $_GET['action'] ) && 'add-single' === $_GET['action'] ) {
		$output = add_single_action();
		?>
		<p><strong>Action output:</strong></p>
		<p>
		<?php
		esc_html_e( $output );
		?>
		</p>
		<br/><br/>
		<a href="<?php get_site_url(); ?>/wp-admin/admin.php?page=growdev-demo">Reset</a>
		<?php
	} else {
		?>
		<p>This page lets an administrator schedule an action. </p>
		<?php
		$url = add_query_arg( 'action', 'add-single' );
		?>
		<li><a href="<?php echo esc_url( $url ); ?>">Add single scheduled action.</a></li>
		<?php

	}
	?>
	</ul>
	<?php
}

/**
 * Add a scheduled action with custom hook.
 * @return string
 */
function add_single_action() {
	// add action parameters
	$timestamp = strtotime( '+2 minutes' );
	$hook      = 'growdev_change_title';
	$args      = array( 'my_custom_arg' => 'custom_value' );
	$group     = '';

	// Action Scheduler API function call
	$job_id = as_schedule_single_action( $timestamp, $hook, $args, $group );

	return 'Action added with Job ID: ' . $job_id;
}

add_action( 'growdev_change_title', __NAMESPACE__ . '\\change_title' );
/**
 * This is the callback for the hook and will change the blog title.
 *
 * @return void
 */
function change_title() {
	update_option( 'blogname', 'New Title!!!' );
}
