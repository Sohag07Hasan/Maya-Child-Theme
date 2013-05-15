<?php
/**
 * @package WordPress
 * @subpackage YIW Themes
 * @customized by: Mahibul Hasan Sohag (hyde.sohag@gmail.com)
 * 
 * Here the first hentry of theme, when all theme will be loaded.
 * On new update of theme, you can not replace this file.
 * You will write here all your custom functions, they remain after upgrade.
 */                                                                               
 
 /* 
  * Truncate the titles
  * */

add_filter('the_title', 'truncate_product_title');
function truncate_product_title($text){
	/*		
	$limit = 5;
	if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
      */
      
      $limit = 35;
      if(strlen($text) > $limit){
			$text = substr($text, 0, $limit) . '...';
	  }
	  
	  return $text;
}
  


//Review manipulation
remove_action('woocommerce_after_shop_loop_item', 'yiw_star_rating');







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
		
		//checkout page
		if(isset($_POST['proceed'])){
			return wp_robot_amazon_checkout();
		}
		
	endif;
	
}


/**
 * redirect to amazon checkout apge
 * */
function wp_robot_amazon_checkout(){
	$cartCookie = Reviewzon_get_cart();
	if($cartCookie){
		$pas = new AmazonPAS();
		$response = $pas->cart_get($cartCookie->cart->cartid, $cartCookie->cart->hmac, null, $cartCookie->cart->country);
		if($response->isOK()){
			if(count($response->body->Cart->CartItems->CartItem) > 0){
				foreach($response->body->Cart->CartItems->CartItem as $cartItem){
					$product_id = get_product_id_byASIN($cartItem->ASIN);
					$quantity = (string) $cartItem->Quantity;
					
					$previous_stat = (int) get_post_meta($product_id, 'total_sales', true);
					
					$previous_stat = ($previous_stat > 0) ? $previous_stat : 0;
					
					$quantity = (int) $quantity + $previous_stat;
					update_post_meta($product_id, 'total_sales', $quantity);
				}
			}
			
			$url = (string)$response->body->Cart->PurchaseURL;
			
			if(!function_exists('wp_redirect')){
				include ABSPATH . '/wp-includes/pluggable.php';
			}
			
			wp_redirect($url);
			exit;
		}
	}
	
	global $woocommerce;
	$woocommerce->add_message("Please check your Cart. Probably it is empty");
}




//mini cart at the top bar
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
    	   <li class="totals"><?php _e( 'Subtotal', 'yiw' ) ?><span class="price"><?php echo (string)$response->body->Cart->SubTotal->FormattedPrice; ?></span></li><?php
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



/***********************  AJAX ACTIONS ******************************/
add_action('wp_enqueue_scripts', 'enqueue_the_cart_actions_js');
function enqueue_the_cart_actions_js(){
	wp_enqueue_script('jquery');
	
	wp_register_script('amazon-products-cat-functionality-handling', get_stylesheet_directory_uri() . '/js/cart-actions.js', array('jquery'));
	
	wp_enqueue_script('amazon-products-cat-functionality-handling');
	
	wp_localize_script('amazon-products-cat-functionality-handling', "AMAZONPRODUCT", array('ajax_url'=>admin_url('admin-ajax.php')));
}


//ajax action
add_action('wp_ajax_amazon_cart_actions', 'amazon_ajax_handle');
add_action('wp_ajax_nopriv_amazon_cart_actions', 'amazon_ajax_handle');
function amazon_ajax_handle(){
	$product_id = (int)$_REQUEST['product_id'];
	$quantity = 1;
	$country = 'us';
	
	if(class_exists('AmazonPAS')):
			$pas = new AmazonPAS();
			$offer_listing_id = array(wprobot_woocommerce::get_amazon_asin($product_id) => $quantity);	
								
			$cartCookie = Reviewzon_get_cart();
			
			if($cartCookie != null){
				$response = $pas->cart_add($cartCookie->cart->cartid, $cartCookie->cart->hmac, $offer_listing_id, null, $cartCookie->cart->country);
										
			}
			else{
				$response = $pas->cart_create($offer_listing_id,null,$country);		
				if($response->isOK()){
					$cookie = array();
					$cartid = (string)$response->body->Cart->CartId;
					$hmac = (string)$response->body->Cart->HMAC;		
					$cart = array("cartid"=>$cartid,"hmac"=>$hmac,"country"=>$country);
					$cookie["cart"] = $cart;
					setcookie('wo_rzon_cart_info', json_encode(wprobot_woocommerce::wo_arrayToObject($cookie)), current_time('timestamp')+7*24*60*60, '/');
				}						
				
			}
		 endif;
		 
		 //checking if the response is successful
		if($response->isOK()) {
	    	wp_robot_amazon_minicart();   		    	   	
		}
		else{
			echo 0;
		}
	exit;
}


//ordering by average rating
add_filter('woocommerce_get_catalog_ordering_args', 'woocommerce_get_catalog_ordering_args', 100, 1);
function woocommerce_get_catalog_ordering_args($args){
	if($_REQUEST['orderby'] == 'avgrating'){
		$args['orderby']  = 'meta_value_num';
		$args['order']    = $order == 'asc' ? 'asc' : 'desc';
		$args['meta_key'] = 'avg_rating';
	}
	
	
	if($_REQUEST['orderby'] == 'reviewcount'){
		$args['orderby']  = 'meta_value_num';
		$args['order']    = $order == 'asc' ? 'asc' : 'desc';
		$args['meta_key'] = 'review_count';
	}
	
	
	if(empty($_REQUEST['orderby'])){
		$args['orderby']  = 'meta_value_num';
		$args['order']    = $order == 'asc' ? 'asc' : 'desc';
		$args['meta_key'] = 'review_count';
	}
	
	
	return $args;
}

//changing the default ordering
add_filter('woocommerce_default_catalog_orderby', 'woocommerce_default_catalog_orderby', 100, 1);
function woocommerce_default_catalog_orderby($orderby){
	return $orderby;
}


add_action('woocommerce_after_add_to_cart_form', 'woocommerce_after_add_to_cart_form');
function woocommerce_after_add_to_cart_form(){
	?>
		
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>
		<a class="addthis_button_pinterest_pinit"></a>
		<a class="addthis_counter addthis_pill_style"></a>
		</div>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-5193f8a9540c1be4"></script>
		<!-- AddThis Button END -->
		
	<?php
}

