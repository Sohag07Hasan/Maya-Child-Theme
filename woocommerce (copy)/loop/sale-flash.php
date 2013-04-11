<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<?php if (wprobot_woocommerce::is_sell_able()) : ?>
	
	<?php echo apply_filters('woocommerce_sale_flash', '<span class="onsale">'.__('Sale!', 'yiw' ).'</span>', $post, $product); ?>
	
<?php endif; ?>
