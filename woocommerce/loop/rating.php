<?php 
	global $post;
	$rating =  get_post_meta( $post->ID, 'avg_rating', true );
	$count = get_post_meta($post->ID, 'review_count', true);
?>

<div class="loop_rating" itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating">
	<div title="Rated <?php echo $rating; ?> out of 5" class="star-rating">
		<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%">
			<span class="rating" itemprop="ratingValue"><?php echo $rating; ?>  out of 5 </span>
		</span>		
	</div>
	<span title="Total <?php echo $count; ?> Reviews" >( <?php echo $count ?> Reviews )</span>
</div>
<div style="clear:both"></div>
