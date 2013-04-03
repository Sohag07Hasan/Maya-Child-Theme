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



//mini cart
function wp_robot_amazon_minicart( $echo = true ) {
	
    global $woocommerce;
    
    ob_start();
	
	$cartCookie = Reviewzon_get_cart();
	
	// quantity
	$qty = 0;
	if($cartCookie){
			$pas = new AmazonPAS();
			$response = $pas->cart_get($cartCookie->cart->cartid, $cartCookie->cart->hmac, null, $cartCookie->cart->country);
			if($response->isOK()){
				foreach($response->body->Cart->CartItems->CartItem as $cartItem){
					$qty += (int)$cartItem->Quantity;
				}
			}
		}
	
	
	
	if ( $qty == 1 )
	   $label = __( 'item', 'yiw' );
	else             
	   $label = __( 'items', 'yiw' );  ?>
	   
	<a class="widget_shopping_cart trigger" href="<?php echo $woocommerce->cart->get_cart_url() ?>">
		<span class="minicart"><?php echo $qty ?> <?php echo $label ?> </span>
	</a>
	
	<?php if ( yiw_get_option('topbar_cart_ribbon_hover') ) : ?>
	<div class="quick-cart">
    	<ul class="cart_list product_list_widget"><?php
    	
    	if ($qty > 0 && isset($response->body->Cart->CartItems->CartItem)) :
    	
            foreach($response->body->Cart->CartItems->CartItem as $cartItem) :
				
				$product_id = get_product_id_byASIN($cartItem->ASIN);	
				$product_name = (string)$cartItem->Title;
				$permalink = get_permalink($product_id);			
               ?>
					<li>
						<a href="<?php echo get_permalink($product_id); ?>"><?php echo apply_filters('woocommerce_cart_widget_product_title', $product_name) ?></a>
						<span class="price"><?php echo apply_filters('woocommerce_cart_item_price_html', (string)$cartItem->Price->FormattedPrice, $post_id); ?></span>
					</li>
			<?php				
				
            endforeach;
            
        else : ?>
            <li class="empty"><?php _e('No products in the cart.', 'yiw' ) ?></li><?php
        endif;   
    	
    	if ($qty > 0 && isset($response->body->Cart->CartItems->CartItem)) :
    	?>
    	   <li class="totals"><?php _e( 'Subtotal', 'yiw' ) ?><span class="price"><?php echo (string)$cartItem->ItemTotal->FormattedPrice; ?></span></li><?php
    	endif; ?>
    	
    	   <li class="view-cart-button"><a class="view-cart-button" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><?php echo apply_filters( 'yiw_topbar_minicart_view_cart', __( 'View cart', 'yiw' ) ) ?></a></li>
    	
    	</ul>
    	
    </div><?php
    endif;
    
    $html = ob_get_clean();
    
    if ( $echo )
        echo $html;
    else
        return $html;
}    


