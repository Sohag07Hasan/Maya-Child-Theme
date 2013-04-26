<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
$rating =  get_post_meta( $post->ID, 'avg_rating', true );
$count = get_post_meta($post->ID, 'review_count', true);

$count = ($count > 0) ? $count : 0;
$rating = ($rating > 0) ? $rating : 0;

?>

<h1 itemprop="name" class="product_title entry-title"><?php echo $post->post_title; ?></h1>

<div style="clear: both"></div>

<div style="margin-top: 3px; float: left;" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf(__( 'Rated %s out of 5', 'woocommerce' ), $rating) ?>">
	<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo  $rating ; ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?></span> 
	
	<?php 
		if($count > 0):
			?>
			<div style="clear: both"></div>
			<div>
				( <?php echo $count; ?> Reviews )
			</div>
			<?php
		endif;
	?>
	
</div>

