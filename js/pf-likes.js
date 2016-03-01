jQuery(document).ready(function($){



    $('.pf-headword').on('click',function() {
/*        e.preventDefault();*/
         //get serialized data from all our option fields      
         var id = $(this).attr('id');  
         var post_id = $(this).data('num'); 

         var params = new Array(id,post_id);

/*         console.log(data);*/
         $.post(pf_likes.ajaxurl, { action:'pf_ajax_headwords', id:id, post_id:post_id} , function(response) {
         
           if (response=='test') {
            console.log('success');
           } else { 
             console.log('fail');
           }
                 
         });
           
       return false; 
               
       });   


	$('.pf-likes').on('click',
	    function() {
    		var link = $(this);
    		if(link.hasClass('active')) return false;
		
    		var id = $(this).attr('id'),
    			postfix = link.find('.pf-likes-postfix').text();
			
    		$.post(pf_likes.ajaxurl, { action:'pf-likes', likes_id:id, case: "like", postfix:postfix }, function(data){
    			link.html(data).addClass('pf-active').attr('title','You already like this').text('');
    		});
		
    		return false;
	});

    $('.pf-neutrals').on('click',
        function() {
            var link = $(this);
            if(link.hasClass('active')) return false;
        
            var id = $(this).attr('id'),
                postfix = link.find('.pf-likes-postfix').text();
            
            $.post(pf_likes.ajaxurl, { action:'pf-likes', likes_id:id, case: "neutral", postfix:postfix }, function(data){
                link.html(data).addClass('pf-active').attr('title','You already voted this').text('');
            });
        
            return false;
    });

    $('.pf-dislikes').on('click',
        function() {
            var link = $(this);
            if(link.hasClass('active')) return false;
        
            var id = $(this).attr('id'),
                postfix = link.find('.pf-likes-postfix').text();
            
            $.post(pf_likes.ajaxurl, { action:'pf-likes', likes_id:id, case: "dislike", postfix:postfix }, function(data){
                link.html(data).addClass('pf-active').attr('title','You already disliked this').text('');
            });
        
            return false;
    });

    $("a[link-out-pos]").click(function() {
        var linkout = $(this).attr("link-out-pos");
        $.post(pf_likes.ajaxurl,{ action:'pf-likes', postid: linkout, case: "link-like", }, function(data){
               
            });
            return false;
    });
    $("a[link-out-neu]").click(function() {
        var linkout = $(this).attr("link-out-neu");
        $.post(pf_likes.ajaxurl,{ action:'pf-likes', postid: linkout, case: "link-neutral",}, function(data){
                
            });
            return false;
    });
    $("a[link-out-neg]").click(function() {
        var linkout = $(this).attr("link-out-neg");
        $.post(pf_likes.ajaxurl,{ action:'pf-likes', postid: linkout, case: "link-dislike",}, function(data){
                
            });
            return false;
    });
/*	if( $('body.ajax-pf-likes').length ) {
        $('.pf-likes').each(function(){
    		var id = $(this).attr('id');
    		$(this).load(pf.ajaxurl, { action:'pf-likes', post_id:id });
    	});
	}*/

});


/*jQuery(document).ready(function($){

    $('#email-submit').on('click',function() {
            var content  = $('this').serialize();
            var nonce    = $('#email-feedback-nonce').val();
            $.post(email_feedback.ajaxurl, { action:'email-feedback', enonce:nonce, content:content }, function(data){
                        $("#email-feedback-result").css('display','block').text("Thank you.");
            })
            
        });

   });*/