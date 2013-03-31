<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;


if(wprobot_woocommerce::$wprobot_post){
	if(strlen(wprobot_woocommerce::$wprobot_post['description']) > 2){
		?>
		<?php echo apply_filters( 'woocommerce_short_description', wprobot_woocommerce::$wprobot_post['description'] ) ?>
		<?php
	}
}

?>
