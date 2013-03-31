<?php
/**
 * Loop Price edited by Mahibul Hasan
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php if(wprobot_woocommerce::$wprobot_post) : ?>

	<span class="price">
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
		
	</span>
	
<?php endif; ?>
