jQuery(document).ready(function ($) {
	
	$('.pf-btn').on('click',function(e){
		$('.pf-preview').hide();
		$('.pf-invisible').show();
		$('#article_lock').hide();
		$('#progressbar').hide();
			e.preventDefault();
	});

	$(function() {
   		$('a[rel=tipsy]').tipsy({fade: false, gravity: 's'});
 	});

});


jQuery(document).ready(function ($) {
    var $pages = $(".steps-content").children("section"),
        $steps = $(".steps-wizard").children("div"),
        totalPages = $pages.length,
        per = 1/totalPages *100,
        count = 0;
    	var oper = '';

    $('#progressbar span').css('width',per + '%');


	console.log(totalPages);
    $(".navigation,.pf-likes,.pf-dislikes,.pf-neutrals,.pf-headword").on("click",function(e){

        e.preventDefault();        
        var navigate = $('.navigation').data("nav");

            count++;
            oper = per *(count+1);

        
        if(count > totalPages){
            count = 0;
        }


        console.log(count);
       	$('#progressbar span').css("width",oper + '%');
        if(count < totalPages && count >=0){

        $steps.hide(500,'easeInOutBack').eq(count).show();

		//show the previous section
		$pages.eq(count).show(); 
		//hide the current section with style
		$pages.eq(count-1).animate({opacity: 0}, {
			step: function(now, mx) {
				//as the opacity of pages.eq(count) reduces to 0 - stored in "now"
				//1. scale previous_fs from 80% to 100%
				scale = 0.8 + (1 - now) * 0.2;
				//2. take pages.eq(count) to the right(50%) - from 0%
				left = ((1-now) * 50)+"%";
				//3. increase opacity of previous_fs to 1 as it moves in
				opacity = 1 - now;
				$pages.eq(count-1).css({'left': left});
				$pages.css({'transform': 'scale('+scale+')', 'opacity': opacity});
			}, 
			duration: 800, 
			complete: function(){
				$pages.eq(count-1).hide();
				animating = false;
			}, 
			//this comes from the custom easing plugin
			easing: 'easeInOutBack'
		});
        }

        if(count + 1 == totalPages){
        	$('.navigation').hide();
        }

    });    
});