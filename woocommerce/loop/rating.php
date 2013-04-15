<?php 
	global $post;
	$rating =  get_post_meta( $post->ID, 'avg_rating', true );
	$count = get_post_meta($post->ID, 'review_count', true);
	
	$count = ($count > 0) ? $count : 0;
	$rating = ($rating > 0) ? $rating : 0;
?>

<div class="loop_rating" itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating">
	<div title="Rated <?php echo $rating; ?> out of 5" class="star-rating">
		<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%">
			<span class="rating" itemprop="ratingValue"><?php echo $rating; ?>  out of 5 </span>
		</span>		
	</div>
	<span title="Total <?php echo $count; ?> Reviews" >( <?php echo ($count) ? $count . ' Reviews' : 'No Reviews Yet' ?> )</span>
</div>
<div style="clear:both"></div>
