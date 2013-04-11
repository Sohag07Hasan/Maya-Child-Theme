<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product;


?>

<?php
	if($product->price < 0) return;
?>


<?php do_action('woocommerce_before_add_to_cart_form'); ?>

<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart" method="post" enctype='multipart/form-data'>

	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

	<?php
		if ( ! $product->is_sold_individually() )
			woocommerce_quantity_input( array(
				'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : 10, $product )
			) );
	?>

	<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __('Add to cart', 'yiw' ), $product->product_type); ?></button>

	<?php do_action('woocommerce_after_add_to_cart_button'); ?>
	
	<div class="clear"></div>

</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>

