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

<?php if(wprobot_woocommerce::$wprobot_post) : ?>

	<p itemprop="price" class="price">
		<?php 
			if(isset(wprobot_woocommerce::$wprobot_post['list_price']) && (wprobot_woocommerce::$wprobot_post['list_price'] < wprobot_woocommerce::$wprobot_post['price'])){
				echo "<del class='amount'>" . wprobot_woocommerce::$wprobot_post['list_price'] . "</del>" ;
			}
		?>
		
		<?php 
			if(isset(wprobot_woocommerce::$wprobot_post['price'])){
				echo "<ins class='amount'>" . wprobot_woocommerce::$wprobot_post['price'] . "</ins>" ;
			}
		?>
		
	</p>
	
<?php endif; ?>


	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>
