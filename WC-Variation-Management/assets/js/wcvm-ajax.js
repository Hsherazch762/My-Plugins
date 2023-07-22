// function wcvm_like_btn_ajax(postId,usrid) {
    
// 	var post_id = postId;
//     var usr_ID = usrid;
// 	jQuery.ajax({
// 		url : wpac_ajax_url.ajax_url,
// 		type : 'post',
// 		data : {
// 			action : 'wpac_like_btn_ajax_action',
// 			pid : post_id,
// 			uid : usr_ID
// 		},
// 		success : function( response ) {
//             jQuery("#wpacAjaxResponse span").html(response);
// 		}
// 	});
// }
// jQuery(document).ready(function(){
// 	let prod_select = document.getElementById("wcvm-product-select");
// jQuery(prod_select).change(function(){
// let product_id = jQuery(prod_select).val();
// 	console.log(product_id);		
// 	jQuery.ajax({
//         url: wcvm_ajax_object.ajax_url, // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
//         data: {
//             'action':'example_ajax_request', // This is our PHP function below
// 			'product_id' : product_id
//         },
//         success:function(data) {
//     // This outputs the result of the ajax request (The Callback)
// //     location.reload();
// 			  //   console.log(data);
// 		console.log("id:", product_id);
//         },
//         error: function(errorThrown){
//             window.alert(errorThrown);
// // 			console.log(errorThrown);
//         }
//     });
// });


// });