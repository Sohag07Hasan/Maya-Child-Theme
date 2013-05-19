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
<?php if ($product->price > 0 && $product->regular_price > $product->price) : ?>
	
	<?php echo apply_filters('woocommerce_sale_flash', '<span class="onsale">'.__('Sale!', 'yiw' ).'</span>', $post, $product); ?>
	
<?php endif; ?>
