<?php
/**
 * Plugin Name:       medtigo UTM Tracker
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       The Plugin is used to save utm parameters in your profile.
 * Version:           1.10.3
 * Requires at least: 5.0
 * Requires PHP:      5.2
 * Author:            decklaration
 * Author URI:        https://decklaration.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       medtigo_utm_tracker
 * Domain Path:       /languages
 */
 /************************************************************************************************************************************************************************************************************************USER EXTRA META***************************************************/
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("UTM information", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="campaignsource"><?php _e("UTM Source"); ?></label></th>
        <td>
            <input readonly type="text" name="campaignsource" id="utm_source" value="<?php echo esc_attr( get_the_author_meta( 'campaignsource', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
    <tr>
        <th><label for="campaignmedium"><?php _e("UTM Medium"); ?></label></th>
        <td>
            <input readonly type="text" name="campaignmedium" id="utm_medium" value="<?php echo esc_attr( get_the_author_meta( 'campaignmedium', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
    <tr>
    <th><label for="postalcode"><?php _e("UTM Campaign"); ?></label></th>
        <td>
            <input readonly type="text" name="campaignname" id="utm_campaign" value="<?php echo esc_attr( get_the_author_meta( 'campaignname', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
    </table>
<?php }
/****for datasaving**/
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'campaignsource', $_POST['campaignsource'] );
    update_user_meta( $user_id, 'campaignmedium', $_POST['campaignmedium'] );
    update_user_meta( $user_id, 'campaignname', $_POST['campaignname'] );
}
/***extra_field show**/
function new_modify_user_table_dlt( $column ) {
    $column['campaignsource'] = 'UTM Source';
	$column['campaignmedium'] = 'UTM Medium';
	$column['campaignname'] = 'UTM Campaign';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table_dlt' );

function new_modify_user_table_row_dlt( $val, $column_name, $user_id ) {
    $meta = get_user_meta($user_id);
	error_reporting(0);
    switch ($column_name) {
        case 'campaignsource' :
            $campaignsource = $meta['campaignsource'] ? $meta['campaignsource'][0] : '';
            return $campaignsource;
		case 'campaignmedium' :
			$campaignmedium = $meta['campaignmedium'] ? $meta['campaignmedium'][0] : '';
            return $campaignmedium;
		case 'campaignname' :
			$campaignname = $meta['campaignname'] ? $meta['campaignname'][0] : '';
            return $campaignname;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row_dlt', 10, 3 );
/************************************************************************************************************************************************************************************************************************USER EXTRA META***************************************************/

function add_this_script_footer(){?>
<script type="text/javascript">
	if(handl_utm && handl_utm.utm_source != 'undefined' && jQuery("meta[property='og:title']").attr("content").toLowerCase().includes("thank you for registration")){
		var reg_url = "<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=register_utm&utm_source=" + handl_utm.utm_source + "&utm_medium=" + handl_utm.utm_medium + "&utm_campaign=" + handl_utm.utm_campaign;
		function httpGet(theUrl)
		{
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", theUrl, false );
			xmlHttp.send(null);
			return xmlHttp.responseText;
		}
		httpGet(reg_url);
}
</script> 
<?php }
add_action('wp_footer', 'add_this_script_footer');

function register_utm() {
if ( isset($_REQUEST) ) {
	$user_id        = get_current_user_id();                            // Get our current user ID
	$um_sr         = sanitize_text_field( $_GET['utm_source'] );
	$um_med        = sanitize_text_field( $_GET['utm_medium'] );
    $um_camp         = sanitize_text_field( $_GET['utm_campaign'] );      // Sanitize our user meta value
    update_user_meta( $user_id, 'campaignsource' , $um_sr);
  update_user_meta( $user_id, 'campaignmedium' , $um_med);
	update_user_meta( $user_id, 'campaignname' , $um_camp);	
echo $um_camp;
}
   die();
}
add_action( 'wp_ajax_register_utm', 'register_utm' );