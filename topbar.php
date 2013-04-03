<?php if ( ! yiw_get_option('show_topbar' ) ) return; ?>

        <?php $show_ribbon = (bool)( function_exists('yiw_minicart') && yiw_get_option('show_topbar_cart_ribbon', 1) ) ?>
        <div id="top">
			<div class="inner">
                <div class="topbar-left">
                <?php if( yiw_get_option( 'topbar_content') == 'static' ) : ?>
                    <p><?php echo do_shortcode( stripslashes( yiw_get_option( 'topbar_text' ) ) ) ?></p>
                <?php endif; ?>
                
                <?php if( yiw_get_option( 'topbar_content' ) == 'twitter' ) : ?>
                <!-- START TWITTER -->
		        <div id="twitter-slider" class="group">
			        <div class="tweets-list"></div>                    
                    <script type="text/javascript">
                        jQuery(function($){         
                            
                            var twitterSlider = function(){      
                                $('.tweets-list ul').addClass('slides');
                                $('.tweets-list').flexslider({
                                    animation: "fade",
                                    slideshowSpeed: <?php echo yiw_get_option( 'topbar_twitter_interval' ); ?> * 1000,
                                    animationDuration: 700,
                                    directionNav: false,             
                                    controlNav: false,             
                                    keyboardNav: false
                                });
                            };
                            
                            
                            $('#twitter-slider .tweets-list').tweetable({
                                username: '<?php echo yiw_get_option( 'topbar_twitter_username' ) ?>',
                                items: <?php echo yiw_get_option( 'topbar_twitter_items' ) ?>,
                                time: false,
                                loaded: twitterSlider
                            });
                            
                            $( document ).ready( function() {
                                var rightWidth = $( '.topbar-right' ).width() + $( '#cart' ).width();
                                
                                $( '#twitter-slider' ).css( 'max-width', ( 940 - 40 - rightWidth ) + 'px' );  
                            });  
                        });
                    </script>	
							    
				</div>       
		        <!-- END TWITTER -->
                <?php endif; ?>
                </div>
                <div class="topbar-right"<?php if ( ! $show_ribbon ) : ?> style="right:0;"<?php endif ?>>
                    <?php get_template_part( 'sidebar', 'topbar' ); ?>
                    <ul class="topbar-level-1">
                        <?php
                        $nav_args = array(
                            'theme_location' => 'topbar',  
						    'items_wrap' => '%3$s',
                            'container' => 'none',
                            'menu_class' => 'topbar-level-1',
                            'depth' => 2,
                            'fallback_cb' => ''
                        );
                        
                        wp_nav_menu( $nav_args ); 
                        ?>
                        <?php 
                        global $yiw_is_woocommerce;
                        if( !yiw_get_option( 'topbar_login' ) ) :
                            if( is_user_logged_in() ) :
                        ?>
                        <li><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Logout', 'yiw' ); ?></a></li>
                        <?php
                            else :
                                if ( $yiw_is_woocommerce ) {
                                    $my_account_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
                                    $label = array();
                                    if ( !yiw_get_option( 'topbar_login' ) )
                                        $label[] = __( 'Login', 'yiw' );
                                    if ( !yiw_get_option( 'topbar_register' ) && get_option('woocommerce_enable_myaccount_registration')=='yes' )
                                        $label[] = __( 'Register', 'yiw' );
                                    
                                    if ( empty( $label ) )
                                        $label = '';
                                    else
                                        $label = implode( '/', $label );
                                } else {
                                    $my_account_url = get_permalink( get_option('jigoshop_myaccount_page_id') );
                                    $label = __( 'Login', 'yiw' );
                                }
                                
                        ?>
                       <li><a href="<?php echo $my_account_url; ?>"><?php echo $label; ?></a></li>
                        <?php
                            endif;
                        endif;
                        
                        if( !$yiw_is_woocommerce AND !yiw_get_option( 'topbar_login' ) AND !yiw_get_option( 'topbar_register' ) ) {
                            echo ' | ';
                        }
                        
                        if( !$yiw_is_woocommerce && !yiw_get_option( 'topbar_register' ) ) :
                            echo '<li>';
                            wp_register( '', '' );             
                            echo '</li>';
                        endif;
                        ?>
                    </ul>
                </div>
                
            
                
				<?php if ( $show_ribbon ) : ?>
                <div id="amazon-cart"> 
			 <!--	<div id="cart" onload="return false;"> -->
					<?php wp_robot_amazon_minicart(); ?>
				</div><!-- #cart -->
				<?php endif ?>
			</div><!-- .inner -->
		</div><!-- #top -->
