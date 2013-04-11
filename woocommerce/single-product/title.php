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

?>
<h1 itemprop="name" class="product_title entry-title"><?php echo $post->post_title; ?></h1>
