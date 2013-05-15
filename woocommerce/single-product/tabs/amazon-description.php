<?php
/**
 * Features tab by Mahibul Hasan
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;



?>

<?php $heading = esc_html( apply_filters('woocommerce_product_description_heading', __('Product Description', 'yiw' ))); ?>
	<h2><?php echo $heading; ?></h2>
<?php 
	$description = get_post_meta($post->ID, 'description', true);
	echo (strlen($description) > 2) ? strip_tags($description, '<p><a><h1><h2><h3><h4><h5><h6><br>') : ''; 
?>

<?php
if( yiw_get_option( 'shop_show_share_socials' ) ) :
	echo do_shortcode( '[share title="' . yiw_get_option( 'shop_share_title' ) . '" socials="' . yiw_get_option( 'shop_share_socials' ) . '"]' );
endif;
?>	
		
	
	
