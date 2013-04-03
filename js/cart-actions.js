jQuery(document).ready(function($){
	
	$('.amazon-add-to-cart').click(function(){
		
		var product = $(this);
		
		//blocking
        product.block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.3, cursor:'none'}});
       
       
       // var product_id = product.attr('data-product_id');
        
        //ajax request
        $.ajax({
			type: 'post',
			url: AMAZONPRODUCT.ajax_url,
			cache: false,
			timeout: 10000,
			
			data: {
				'action': 'amazon_cart_actions',
				'product_id': product.attr('data-product_id')
			},
			
			success: function(response){
				//chaning the minicart
				if(response){
					$('#amazon-cart').html(response);
					
					//html and css changing
					product.html('ADDED');
					product.addClass('added');
					
					//unblocing	
					product.unblock();	
				}		
			},
			
			error: function(jqXHR, textStatus, errorThrown){
				//$('#footer').html(textStatus);
				alert(textStatus);
			}
		});        
       
        
       return false;             
    });
            
    
});
