<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;


?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

<?php if($product->price > 0) : ?>

	<p itemprop="price" class="price">
		<?php 
			if($product->regular_price > $product->price){
				echo "<del class='amount'>" . woocommerce_price($product->regular_price) . "</del>" ;
			}
		?>
		
		<?php 
				echo "<ins class='amount'>" . woocommerce_price($product->price) . "</ins>" ;			
		?>
		
	</p>
	
<?php endif; ?>


	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/InStock" />

</div>
