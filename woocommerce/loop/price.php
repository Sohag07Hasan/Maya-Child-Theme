<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price">
		<?php 
			if($product->regular_price > $product->price){
				echo "<del class='amount'>" . woocommerce_price($product->regular_price) . "</del>" ;
			}
			
			echo "<ins class='amount'>" . woocommerce_price($product->price) . "</ins>" ;
		?>
	</span>
	
<?php endif; ?> 
	
