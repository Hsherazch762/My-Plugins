<?php
function wcvm_settings_page_html() {
    //Check if current user have admin access.
    if(!is_admin()) {
        return;
    }
    ?>
        <div class="wrap">
            <h1 style="padding:10px; background:#333;color:#fff; width:100%;"><?= esc_html(get_admin_page_title()); ?></h1>
<!--             <form action="options.php" method="post"> -->
                <?php 
                    // output security fields for the registered setting "wpac-settings"
                    settings_fields( 'wcvm-settings' );

                    // output setting sections and their fields
                    // (sections are registered for "wpac-settings", each field is registered to a specific section)
                    do_settings_sections( 'wcvm-settings' );

                    // output save settings button
//                     submit_button( 'Save Changes' );
                ?>
<!--             </form> -->
        </div>
    <?

}

//Top Level Administration Menu
function wcvm_register_menu_page() {
    add_menu_page( 'Dashboard', 'WC Variation Management', 'manage_options', 'wcvm-settings', 'wcvm_settings_page_html', 'dashicons-embed-generic', 30 );
}
add_action('admin_menu', 'wcvm_register_menu_page');


// Register settings, sections & fields.
function wcvm_plugin_settings(){

    // register 2 new settings for "wpac-settings" page
    register_setting( 'wcvm-settings', 'wcvm_admin_commission_label' );
    register_setting( 'wcvm-settings', 'wcvm_admin_wallet_label' );
	register_setting( 'wcvm-settings', 'wcvm_royalty_fee_label' );

    // register a new section in the "wpac-setings" page
    add_settings_section( 'wcvm_label_settings_section', 'WC Variations Management', 'wcvm_plugin_settings_section_cb', 'wcvm-settings' );

    // register a new field in the "wpac-settings" page
    add_settings_field( 'wcvm_admin_variation_list_field', 'Available Variable Products List', 'wcvm_admin_variation_list_field', 'wcvm-settings', 'wcvm_label_settings_section' );
}
add_action('admin_init', 'wcvm_plugin_settings');

// Section callback function
function wcvm_plugin_settings_section_cb(){
    //echo '<p>Define Button Labels</p>';
}

// Field callback function
function wcvm_admin_variation_list_field(){ 
	$args = array(
    'type' => 'variable',
	'hide_empty' => true,
		'status' => 'publish'
	);
	$products = wc_get_products( $args );
    ?>
<select id="wcvm-product-select" style="width:400px;">
<option value="">Choose Here</option>
	<?php
		foreach($products as $item_id => $item ){
		//$attributes= get_post_meta( $item->id , '_product_attributes' );
?>
  <option value="<?= $item->id ?>"  data-product-name="<?= $item->name ?>"><?= $item->name ?></option>

<?php
}
?>
</select>
<div class="container" style="margin-top:50px;">
<div id="attribute-container">Attributes List<br></div>
	<div id="attribute_listing">
		<br>
		<table id="variations-listing" border="1"></table>
</div>
</div>
<script>

// Script for fetching Data	
jQuery(document).ready(function(){
	let prod_select = document.getElementById("wcvm-product-select");
	let attrib_select = document.getElementById("wcvm-attrib-select");
	let	attrib_container = document.getElementById("attribute-container");
jQuery(prod_select).change(function(){
	jQuery("#attribute-container").empty();
	jQuery('table#variations-listing').empty();
let product_id = jQuery(prod_select).val();
	jQuery('#wcvm-attrib-select').empty();
	console.log(product_id);
	fetch('/wp-admin/admin-ajax.php?action=example_ajax_request&product_id='+product_id, {
    method: 'GET',
    headers: {
        'Accept': 'application/json'
    },
}).then((response) => response.json())
   .then((data) => {
		console.log(data);
// 	let ddb = JSON.stringify(data);
// let db = JSON.parse(ddb);
// 		console.log(db);
	jQuery.each(data,  function(key,value){
		var attibuteLabel = document.createElement("h3");
		var selectList = document.createElement("select");
		attibuteLabel.innerText = value['name'];
		attrib_container.appendChild(attibuteLabel);
		let contianers = attrib_container.appendChild(selectList);
		selectList.setAttribute("id", value['name']);
		let id_name =	document.getElementById(value['name']);
			let option_val = value['options'];
				for (let i = 0; i < option_val.length; i++) {
 
		fetch('/wp-admin/admin-ajax.php?action=get_custom_tag_attribute_name_by_id&term_id='+option_val[i],{
    method: 'GET',
    headers: {
        'Accept': 'application/json'
    },
}).then((response) => response.json())
   .then((data) => {
			data.forEach(function(item, index) {
				var option = document.createElement("option");
				option.setAttribute("value", item);
				console.log(item);
			  option.text = item;
			  id_name.appendChild(option);	});	
					});
														}	

	//	attribute_container
// 						console.log(value['name']);
// 					 jQuery('#wcvm-attrib-select').append('<option value="'+ key['name'] +'">'+ key +'</option>');
                            });
				
		

});	
	
	
	setTimeout(function() {

				
		jQuery(document).ready(function(){
 			let attributes = [];
       jQuery("#attribute-container select").change(function(){
		   jQuery('table#variations-listing').empty();
      // let attributes_length = jQuery("#attribute-container select").length;
        jQuery("#attribute-container select").each (function () {
			attributes.push(jQuery(this).children(":selected").val());
// 		atrributes.push(jQuery(this).children().slice(0,attributes_length).val());
     });    
		  let final_data = attributes.toString().toLowerCase();
		   console.log("Final Data",final_data);
		  // debugger;
		   fetch('/wp-admin/admin-ajax.php?action=get_variation_details_by_pid&p_id='+product_id+'&variations='+final_data,{
    method: 'GET',
    headers: {
        'Accept': 'application/json'
    },
}).then((response) => response.json())
   .then((data) => {
			   let content ="";
			    content += '<tr><td><b>Product Name</b></td><td><b>Variation Name</b></td><td><b>Price</b></td></tr>';
				 content += '<tr><td>'+  data['name'] + '</td><td>'+  data['variations'] + '</td><td>'+  data['price'] + '</td></tr>';
			   			jQuery('table#variations-listing').append(content);   
		});	
		attributes = [];
});   
            
});
				
}, 3000);
	
	
}); 
	
//	$("attribute_container").click(function(){

  //  });
		
// 		jQuery( "#attribute_container" ).on( "DOMSubtreeModified" ,function() {
//  	 console.log("changes");
// 	});
	
// 	jQuery('#wcvm-attrib-select').change(function(){
// 		let prod_id = jQuery(prod_select).val();
// 		jQuery('#variation-table').empty();
// 		fetch('/wp-admin/admin-ajax.php?action=wcvm_variation_ajax_request&product_id='+prod_id, {
//     method: 'GET',
//     headers: {
//         'Accept': 'application/json'
//     },
// }).then((response) => response.json())
//    .then((data) => {
// 			jQuery.each(data, function(key,value){
// 			jQuery('#variation-table').append('<tr><td>'+value+'</td><td>Name </td></tr>');
// 			});
// 			console.log(data);
// });	

// });
				
			
						
// 	jQuery('#attribute-container').on('DOMTreeModified', function(){
//        jQuery('#attribute-container select').change(function(){
			  
// 		//let ab = jQuery("#attribute-container select")[i].val();
		
//   			console.log("chnaged");

// });
// });
	
});
	
	
	
</script>
    <?php
}
function example_ajax_request(){
if ( isset($_REQUEST) ) {
	//$attributes_list = array();
		$product = wc_get_product($_REQUEST['product_id']);
	$attributes = $product->get_attributes();
// 	$name =	get_term( 16 )->name;
// 	echo $name;	
	$flowers = array();
	foreach ( $attributes as $attribute ) {

	if ( is_object($attribute) ) {

		// Array of attribute data
		//$attribute_data = $attribute->get_data();
// 		echo $attribute_data['id'];
// 		echo $attribute_data['name'];
// 		echo "<pre>";
// 		print_r($attribute_data);
// 		echo "</pre>";
		// Do what you need to do...
		
	$flow= array_push($flowers, $attribute->get_data());
		//print_r($flowers);
	}
}
	echo json_encode($flowers);
// $attributes =	get_post_meta( $_REQUEST['product_id'] , '_product_attributes' );
// 		echo "<pre>";
// 		print_r($attributes);
// 		echo "</pre>";
	
// 	foreach($attributes as $key => $item){
// 	foreach($item as $value){
// 	array_push($attributes_list, $value['name']);
// }
// 			}
	
// 	 echo json_encode($attributes_list);
 	exit;
//exit;
	}
}
add_action( 'wp_ajax_example_ajax_request', 'example_ajax_request' );
// Attribute Selector
function wcvm_variation_ajax_request(){
	if ( isset($_REQUEST) ) {
		$attrib_list = array();
		$product_id = $_REQUEST['product_id'];
// 			global  $wpdb, $table_prefix;
// 	$table_name = $table_prefix. 'posts';
// 	$result = $wpdb->get_results ( "SELECT * FROM  $table_name WHERE post_parent= '$product_id' AND  post_type='product_variation'" );
// 	//$creator_id = $result['0']->creator_id;	
	
// 				foreach ($result as $key){
// // 			echo "<pre>";
// // 			echo $item;
// // 			echo "</pre>";
//  			echo $key->ID;

// 		}
	$product = wc_get_product($product_id);
$variations = $product->get_available_variations();
		
		echo "<pre>";
		print_r($variations['attributes']);
		echo "</pre>";
	$current_products = $product->get_children();
		$list = json_encode($current_products);
		echo $list;
		//echo "<pre>";
// 			global  $wpdb, $table_prefix;
// 	$table_name = $table_prefix. 'posts';
// 	$result = $wpdb->get_results ( "SELECT post_excerpt FROM  $table_name WHERE post_parent= '$product_id'" );	
// 		foreach ($result as $key=>$item){
// 		array_push($attrib_list, $item->post_excerpt);
// 			}
exit;
}
}
add_action( 'wp_ajax_wcvm_variation_ajax_request', 'wcvm_variation_ajax_request' );
function get_custom_tag_attribute_name_by_id(){
 	if ( isset($_REQUEST) ) {
// 	$term_name = get_term($_REQUEST['term_id'])->name;	
	$term_name = get_term($_REQUEST['term_id'])->name;
	}
	echo json_encode(array($term_name));
	exit;
	// Get products with the "Excellent" or "Modern" tags.
}
add_action( 'wp_ajax_get_custom_tag_attribute_name_by_id', 'get_custom_tag_attribute_name_by_id' );
// Get Variatiosn with Price
function get_variation_details_by_pid(){
if ( isset($_REQUEST) ) {
$product_variations = new WC_Product_Variable( $_REQUEST['p_id'] );
 $product_variations = $product_variations->get_available_variations();
	//$data = array();
	$attribute_value_list = explode(",", $_REQUEST['variations'] );
	$variationArray = [];
	foreach($product_variations as $key){
		$attribtes  = array_values($key['attributes']);
		if($attribtes === array_intersect($attribtes, $attribute_value_list) && $attribute_value_list === array_intersect($attribute_value_list, $attribtes)) {
			$variationArray['name'] = $key['image']['title'];
			$variationArray['variations'] = $_REQUEST['variations'];
			$variationArray['price'] = $key['display_price'];
			
		}
		
}
	echo  json_encode($variationArray);
}
	exit;
}
add_action( 'wp_ajax_get_variation_details_by_pid', 'get_variation_details_by_pid' );