<?php
/**
 * @package Custom metabox.
 * @version 1.0
 */
/*
Plugin Name: Meta Box Store.
Description: This Plugin useing for metabox formate.
Author: Rejuan Ahamed Jihad.
Version: 1.0
Author URI: http://rejuancse@gmail.com
*/

# Prevent direct rowing

if( !defined('ABSPATH') ) exit;

/*===============================
*======== Custom Meta Box =======
=================================*/
Function Wp_meta_box_store(){

	$mult_posts = array( 'post', 'page' );

	foreach ($mult_posts as $mult_post) {
		add_meta_box(
			'meta_box_id', 					# metabox id
			__('Author Bio', 'textdomain'),	# Title 
			'wp_meta_box_call_back_func_store', 	# Callback Function 
			$mult_post, 					# Post Type
			'normal'						# textcontent
		);
	}
	
}
add_action( 'add_meta_boxes', 'Wp_meta_box_store' );


function wp_meta_box_call_back_func_store($post){ 

	#Call get post meta.
	$author_name = get_post_meta($post->ID, 'author_name', true);
	$product_name = get_post_meta($post->ID, 'product_name', true);
	$product_description = get_post_meta($post->ID, 'product_description', true);

	# For security Checking.
	wp_nonce_field('product_nonce_action', 'product_nonce_name');

?>
	<p>
		<label for="author_name">Author Name:</label>
		<input class="widefat" id="author_name" type="text" name="author_name" value="<?php echo $author_name; ?>">
	</p>

	<p>
		<label for="product_name">Product Name:</label>
		<input class="widefat" id="product_name" type="text" name="product_name" value="<?php echo $product_name; ?>">
	</p>

	<p>
		<label for="product_description">Produt Description:</label>
		<textarea class="widefat" id="product_description" name="product_description" cols="30" rows="10"><?php echo $product_description; ?></textarea>
	</p>

<?php }

# Data save in custom metabox field
function meta_box_save_func_store($post_id){

	# Doing autosave then return.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	# If the nonce is not present there or we can not versify it.
	if ( !isset($_POST['product_nonce_name']) || !wp_verify_nonce($_POST['product_nonce_name'], 'product_nonce_action' ))
		return;

	# Save Author Name 
	if (isset($_POST['author_name']) && ($_POST['author_name'] != '') ) {
	 	update_post_meta($post_id, 'author_name', esc_html($_POST['author_name']));
	 } 

	# Save data Product name.
	if (isset($_POST['product_name']) && ($_POST['product_name'] != '')) {
		update_post_meta($post_id, 'product_name', esc_html($_POST['product_name']));
	}

	# Save data Product Description.
	if (isset($_POST['product_description']) && ($_POST['product_description'] != '')) {
		update_post_meta($post_id, 'product_description', esc_html($_POST['product_description']));
	}


}
add_action( 'save_post', 'meta_box_save_func_store' );

# Custom metabox field end.