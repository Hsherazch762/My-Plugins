<?php
/*
	Plugin Name: medtigoNotify
	Plugin URI: #
	Description: This plugin is generated to show Learndash Notifications.
	Version: 1.0.0
	Author: Deck Dev Team
	Author URI: #
	License: GPLv2 or later
	Text Domain: medtigoNotify
	*/
//Die if Directly open link of plugin file
if (!defined('WPINC')){
	die;
}
//To check Cosntant Define
if (!defined('MNP_PLUGIN_DIR')){ 
	define('MNP_PLUGIN_DIR', plugin_dir_url(__FILE__));
}

//To check weather the function is already available
if(! function_exists('medtigo_Notify_scripts')){
	function medtigo_Notify_scripts(){
		wp_enqueue_style( 'mnp-css', MNP_PLUGIN_DIR. 'css/toastr.min.css', '5.2.9');
		wp_enqueue_script( 'toastr-js', MNP_PLUGIN_DIR. 'js/toastr.min.js', 'jQuery', '5.2.9', true );

	}
	add_action('wp_enqueue_scripts', 'medtigo_Notify_scripts');
}

function MNP_embed(){?>
<script>
	console.log('Here');
	$ = jQuery.noConflict();

	let users = '<?php echo medtigo_Notify_user_callback(); ?>';
	try {

		users = JSON.parse(users).map(user => {
			return {
				name: user.name.replaceAll('+', ' '),
				title: user.title.replaceAll('+', ' ')
			}
		})
		console.log("Users::", users)
	} catch(e) {
		console.log(e.message)
	}

	$(document).ready(function(){

		let index = 0

		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-bottom-left",
			"preventDuplicates": true,
			"onclick": true,
			"showDuration": "10000",
			"hideDuration": "10000",
			"timeOut": "5000",
			"extendedTimeOut": "100",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		}

		setInterval(() => {

			toastr.success(unescape(users[index].title), unescape((users[index].name)+"&nbsp;Enrolled in"))

			index >= users.length ? index = 0 : index++
		}, 13000);

	});
</script><?php
					}

add_action('wp_footer', 'MNP_embed');

function medtigo_Notify_user_callback()
{

	global $wpdb;
	$results = $wpdb->get_results(
		'SELECT T.user_id, T.post_id, T.activity_started, T.post_title, U.display_name
		FROM ( SELECT ld.user_id,ld.post_id,ld.activity_started,post.post_title 
			FROM '.$wpdb->prefix.'learndash_user_activity AS ld JOIN '.$wpdb->prefix.'posts as post ON ld.post_id=post.ID 
			WHERE activity_type="access" GROUP BY ld.user_id DESC ) AS T JOIN '.$wpdb->prefix.'users AS U ON T.user_id = U.ID
	WHERE U.display_name LIKE \'% %\' AND  U.display_name NOT LIKE \'%.%\' AND U.display_name NOT LIKE \'%-%\' LIMIT 20');


	$lmsUsers = [];
	foreach($results as $result) {

		$full_name = 	$result->display_name;
		$frst_name = explode(" ", $full_name);
		$lmsUsers[] = ['name' =>$frst_name[0], 'title' => $result->post_title];
	}

	//TO check weather the data is avaiable in array or not.
	//echo(json_encode($lmsUsers));
	return json_encode($lmsUsers);
}

add_action('wp_ajax_medtigo_notifications', 'medtigo_Notify_user_callback');
add_action('wp_ajax_nopriv_medtigo_notifications', 'medtigo_Notify_user_callback');
?>