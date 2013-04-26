<?php
/**
 * Cart Page edited by Mahibul Hasan Sohag
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
  
global $woocommerce;

?>

<?php $woocommerce->show_messages(); ?>


<?php
	
	$cart_available = false;
	if(class_exists('AmazonPAS')){
		$cartCookie = Reviewzon_get_cart();
		if($cartCookie){
			$pas = new AmazonPAS();
			$response = $pas->cart_get($cartCookie->cart->cartid, $cartCookie->cart->hmac, null, $cartCookie->cart->country);
			if($response->isOK()){
				if(count($response->body->Cart->CartItems->CartItem) > 0){
					$cart_available = true;
				}
			}
		}
	}
?>


<?php
	if(!$cart_available){
		woocommerce_get_template('cart/amazon-cart-empty.php');
		return;
	}
?>


<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
<?php do_action( 'woocommerce_before_cart_table' ); ?>
<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e('Product', 'woocommerce'); ?></th>
			<th class="product-price"><?php _e('Price', 'woocommerce'); ?></th>
			<th class="product-quantity"><?php _e('Quantity', 'woocommerce'); ?></th>
			<th class="product-subtotal"><?php _e('Total', 'woocommerce'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		
		
							
				$cart_page_link = get_permalink(woocommerce_get_page_id('cart'));
				
				
			
					if(isset($response->body->Cart->CartItems->CartItem)){
						foreach($response->body->Cart->CartItems->CartItem as $cartItem){
							
							$product_id = get_product_id_byASIN($cartItem->ASIN);
							
							?>
							<tr class = "<?php echo esc_attr( apply_filters('woocommerce_cart_table_item_class', 'cart_table_item')); ?>">
								
								<!-- Remove from cart link -->
								<td class="product-remove">
									<?php
										
										$product_remove_link = $cart_page_link . '?Reviewzonaction=remove&item-id=' . $cartItem->CartItemId;
																			
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url($product_remove_link), __('Remove this item', 'woocommerce') ));
									?>
								</td>
								
								<!-- The thumbnail -->
								<td class="product-thumbnail">
									<?php
										$thumbnail = get_cart_page_image($product_id);
										printf('<a href="%s">%s</a>', esc_url(get_permalink($product_id)), $thumbnail);
									?>
								</td>
								
								<!-- Product Name -->
								<td class="product-name">
									<?php
										$permalink = get_permalink($product_id);
										$link_title = (string)$cartItem->Title;
										echo "<a href='$permalink' title='$link_title' target='_blank'> $link_title </a>"; 
									?>
								</td>
								
								<!-- Product price -->
								<td class="product-price">
									<?php
										echo (string)$cartItem->Price->FormattedPrice;
									?>
								</td>
								
								<!-- Quantity inputs -->
								<td class="product-quantity">
									<?php
									
										$product_quantity = sprintf( '<div class="quantity"><input name="cart[%s][qty]" data-min="%s" data-max="%s" value="%s" size="4" title="Qty" class="input-text qty text" maxlength="12" /></div>', $cartItem->CartItemId, 1, 10, (string)$cartItem->Quantity );
										
										echo $product_quantity;
										
									?>
								</td>
								
								<!-- Product subtotal -->
								<td class="product-subtotal">
									<?php
										echo (string)$cartItem->ItemTotal->FormattedPrice;
									?>
								</td>
										
							</tr>
							<?php
						}
					}
				
			
			
		
		
		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td>&nbsp; </td>
			<td> &nbsp;</td>
			<td> &nbsp;</td>
			<td> &nbsp;</td>
			<td>
				<input type="hidden" name="Reviewzon_cart_updated" value="Y" />					
				<input type="submit" class="button" name="update_cart" value="<?php _e('Update Cart', 'woocommerce'); ?>" /> 
			</td>
			<td>
				<!-- <a href="<?php //echo (string)$response->body->Cart->PurchaseURL ?>"> <input class="checkout-button button alt" type="button" value="<?php _e('Proceed to Checkout &rarr;', 'woocommerce'); ?>"></a>	 -->
				<input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'Proceed to Checkout &rarr;', 'woocommerce' ); ?>" />
			</td>		
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>
<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<div class="cart-collaterals">

	<?php do_action('woocommerce_cart_collaterals'); ?>

	<?php //woocommerce_cart_totals(); ?>

	<?php //woocommerce_shipping_calculator(); ?>
	
	<div class="cart_totals">
		<h2><?php _e('Cart Totals', 'woocommerce'); ?></h2>
		<table cellspacing="0" cellpadding="0">
			<tr class="cart-subtotal">
				<th><strong><?php _e('Cart Subtotal', 'woocommerce'); ?></strong></th>
				<td><strong><?php echo (string)$response->body->Cart->SubTotal->FormattedPrice; ?></strong></td>
			</tr>
		</table>
	</div>
	
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
