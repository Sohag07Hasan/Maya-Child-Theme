<?php
/**
 * Reviews tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( comments_open() ) : ?>
	<li class="reviews_tab"><a href="#tab-reviews"><?php echo apply_filters( 'yiw_shop_tab_reviews_label', __('Reviews', 'woocommerce' ) ); ?><?php echo comments_number(' (0)', ' (1)', ' (%)'); ?></a></li>
<?php endif; ?>