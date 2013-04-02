<?php
/**
 * @package WordPress
 * @subpackage YIW Themes
 * 
 * Here the first hentry of theme, when all theme will be loaded.
 * On new update of theme, you can not replace this file.
 * You will write here all your custom functions, they remain after upgrade.
 */                                                                               



/********************************CART UTILITIES********************************************/

//Removing shopping cart managements
function get_product_id_byASIN($asin){
	global $wpdb;
	return $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'ASIN' AND meta_value = '$asin'");
}


//return cart page images
function get_cart_page_image($post_id, $size = 'shop_thumbnail', $attr = array()){
	if(has_post_thumbnail($post_id)){
		$image = get_the_post_thumbnail($post_id, $size, $attr);
	}
	else{
		$imgae = woocommerce_placeholder_img( $size );
	}
	
	return $image;
}

/*
 * update the cart
 * */
 
add_action('init', 'Reviewzon_remove_an_item');
function Reviewzon_remove_an_item(){
	if($_GET['Reviewzonaction'] == 'remove') :
		$cartCookie = Reviewzon_get_cart();
		if($cartCookie){
			$cart_item_id = array(
				$_GET['item-id'] => 0
			);
						
			$pas = new AmazonPAS();
			$response = $pas->cart_modify($cartCookie->cart->cartid, $cartCookie->cart->hmac, $cart_item_id, null, $cartCookie->cart->country);
			
			if($response->isOK()){
				global $woocommerce;
				$woocommerce->add_message("Item removed successfully");
			}
		}
	endif;
}


/*
 * returns the current cart from cookie
 * */

function Reviewzon_get_cart(){
	return json_decode(stripslashes($_COOKIE["wo_rzon_cart_info"]));
}

//update cart command
add_action( 'init', 'Reviewzon_cart_update' );
function Reviewzon_cart_update(){
	if($_POST['Reviewzon_cart_updated'] == "Y") :
		$cart_item_id = array();
		if(is_array($_POST['cart'])){
			foreach($_POST['cart'] as $item_id => $quantity){
				$cart_item_id[$item_id] = $quantity['qty'];
			}
			
			$cartCookie = Reviewzon_get_cart();
			if($cartCookie){				
				$pas = new AmazonPAS();
				$response = $pas->cart_modify($cartCookie->cart->cartid, $cartCookie->cart->hmac, $cart_item_id, null, $cartCookie->cart->country);				
				if($response->isOK()){
					global $woocommerce;
					$woocommerce->add_message("Cart Updated");
				}
			}
		}		
		
	endif;
	
}
