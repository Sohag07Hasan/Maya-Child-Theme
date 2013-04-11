<?php
/**
 * Loop Add to Cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 * 
 * @ Custmozied by  Mahibul Hasan SOhag(hyde.sohag@gmail.com)
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
global $post, $product, $woocommerce_loop;

if ( isset( $woocommerce_loop['style'] ) )
    $style = $woocommerce_loop['style'];
else
    $style = yiw_get_option( 'shop_products_style', 'ribbon' );    
                

//amazon functionalities
if(!wprobot_woocommerce::is_sell_able()) return;

?>


<?php

	$link 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
	$label 	= apply_filters('add_to_cart_text', yiw_get_option( 'shop_button_addtocart_label', __('Add to cart', 'yiw')));
?>
	
	<div class="buttons">
        <?php if ( $style == 'traditional' ) : ?>
			<a href="<?php the_permalink(); ?>" class="details"><?php echo yiw_get_option( 'shop_button_details_label' ) ?></a>
		<?php endif; ?>
		
	    <a href="<?php echo $link ?>" onclick="return false" rel="nofollow" data-product_id="<?php echo $product->id ?>" class="amazon-add-to-cart add_to_cart_button product_type_<?php echo $product->product_type ?>"><?php echo $label ?></a>
	</div>
