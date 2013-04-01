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

<?php $heading = esc_html( apply_filters('woocommerce_product_description_heading', __('Features', 'yiw' ))); ?>
<?php echo wprobot_woocommerce::$wprobot_post['features']; ?>

<?php
if( yiw_get_option( 'shop_show_share_socials' ) ) :
	echo do_shortcode( '[share title="' . yiw_get_option( 'shop_share_title' ) . '" socials="' . yiw_get_option( 'shop_share_socials' ) . '"]' );
endif;
?>	
		
	
	
