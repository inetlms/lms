/* =========================================================

// jquery.fader.js

// Datum: 2009-03-10
// Author: Philip Floetotto
// Version: 1.0
// Mail: mmeier23@gmail.com
// Web: http://philipf.alphacomplex.org/showroom/

// based on the innerfade plugin by medienfreund.de
// Web: http://medienfreunde.com/lab/innerfade/

 *
 *  <ul id="news"> 
 *      <li>content 1</li>
 *      <li>content 2</li>
 *      <li>content 3</li>
 *  </ul>
 *  
 *  $('#news').fader({ 
 *	  animationtype: Type of animation 'fade' or 'slide' (Default: 'fade'), 
 *	  speed: Fading-/Sliding-Speed in milliseconds (Default: '600'), 
 *	  timeout: Time between the fades in milliseconds (Default: '2000'), 
 * 	  containerheight: Height of the containing element in any css-height-value (Default: 'auto'),
 *	  runningclass: CSS-Class which the container getâ€™s applied (Default: 'fader'),
 *	  previousButtonClass: CSS-Class of the previous Button. (Default: 'previousButton') Can be used to set other elements to control the fader,
 *	  nextButtonClass: CSS-Class of the next Button. (Default: 'nextButton'),
 *	  previousButtonText: text inside the previousButton (Default: '&nbsp;') Can be an image or HTML,
 *	  nextButtonText: text inside the nextButton (Default: '&nbsp;') Can be an image or HTML,
 *	  createButtons: true for false if you want the plugin to create navigation buttons (prev and next) automatically (Default: 'true'),
 *	  play: set to start or stop animation (Default: 'true'),
 *  }); 
 *

// ========================================================= */


(function($) {

    $.fn.fader = function(options) {
        return this.each(function() {   
            $.fader(this, options);
        });
    };

    $.fader = function(container, options) {
        var o = {
        	'animationType':    'fade',
            'speed':            600, // please use only numbers because of the jumpNav fade!! 600 is normal, 1200 is slow
            'type':             'sequence',
            'timeout':          2000,
            'containerHeight':  'auto',
            'runningclass':     'fader',
            'previousButtonClass':     'previousButton',
            'nextButtonClass':     'nextButton',
            'previousButtonText':     '&nbsp;',
            'nextButtonText':     '&nbsp;',
            'createButtons':     true,
            'play':     true
        };
        if (options)
            $.extend(o, options);
        
        // checks
        if(isNaN(o.speed)){
        	alert('The speed settings needs to be in seconds, not a string');
        }
        
        // wrap nav and container
        var $container = $(container);
        if (!$container.parent().hasClass('fader-container')){
        	
        	var $faderNav = $("<div>")
    			.addClass('fader-nav')
    			.prepend($("<span>").addClass('fader-jumpNav'))
            	.prepend($("<span>").addClass('fader-navButtons'));
            	
        	$container
				.wrap('<div></div>')
				.before($faderNav)
        		.parent()
        			.addClass('fader-container');
            	
        }
        
        $container.parent = $container.parent('.fader-container');
        $jumpNav = $('.fader-jumpNav',$container.parent);
        
        
        // create Buttons
        if(o.createButtons){
        	$navButtons = $('.fader-navButtons',$container.parent);
        	var $previousButton = $('<a>')
	    		.addClass(o.previousButtonClass)
	    		.html(o.previousButtonText)
	    		.attr('title','next slide')
	    		.appendTo($navButtons);
        	var $nextButton = $('<a>')
        		.addClass(o.nextButtonClass)
        		.html(o.nextButtonText)
        		.attr('title','next slide')
        		.appendTo($navButtons);
        }else{
        	var $nextButton = $('.'+o.nextButtonClass);
        	var $previousButton = $('.'+o.previousButtonClass);
        }
         
        
        // hiding the elements and attaching the innerFade
        $container.o = o;
        $container.slides = [];
        $container.jumpNavAnchors = [];
        
        
        // set css properties for the elements
        var elements = $container.children();
        if (elements.length > 1) {
        	
        	$container
        		.css('position', 'relative')
        		.addClass($container.o.runningclass);
        	
            for (var i = 0; i < elements.length; i++) {
                
            	var anchor = $('<a>')
				            	.attr('id',"fader-jump-"+i.toString())
				            	.attr('style','FILTER: alpha(opacity=100); ZOOM: 1')
				            	.html(i+1)
				            	.appendTo($jumpNav);
            	
            	
            	var element = $(elements[i])
			                	.css('z-index', String(elements.length-i))
			                	.css('position', 'absolute')
			                	.hide();
                
                // assigning slide to jumpnav and vice versa
            	element.slideNumber = i;
            	anchor.data('slide',element);
            	element.data('anchor',anchor);
                $container.slides.push(element);
                $container.jumpNavAnchors.push(anchor);
            };
            
           
            
            $container.currentSlide = $container.slides[0];
            $container.nextSlide = $container.slides[1];
            
            // initialise the sequence
            $container.currentSlide.data('anchor').addClass('active');
            $container.currentSlide.show();
            $.fader.timedChange($container);
            
            // set the fader container height
            if($container.o.containerHeight=='auto')
            	$container.o.containerHeight = $container.slides[0].eq(0).get(0).clientHeight + $faderNav.eq(0).get(0).clientHeight;
            $container.parent.css({'height':$container.o.containerHeight});
            
                
		}
        
        // attach events to jumpNav
        $.each($container.jumpNavAnchors,function(){
        	this.click(function(){
            	$container.nextSlide = $(this).data('slide');
            	$.fader.forceChange($container);
            });
        });
        
        
        
        // attach events to prev and next
        $nextButton.click(function(){
        	if($container.currentSlide.slideNumber + 1 > ($container.slides.length-1)){
	        	$container.nextSlide =	$container.slides[0];
	        }else{
	        	$container.nextSlide = $container.slides[$container.currentSlide.slideNumber + 1];
	        }
        	$.fader.forceChange($container);
        });
        
        $previousButton.click(function(){
        	if($container.currentSlide.slideNumber - 1 < 0){
	        	$container.nextSlide =	$container.slides[$container.slides.length -1];
	        }else{
	        	$container.nextSlide = $container.slides[$container.currentSlide.slideNumber - 1];
	        }
        	$.fader.forceChange($container);
        });
        
    };
        
    $.fader.forceChange = function($container){
        clearTimeout($container.innerFadeTimer);
        $container.o.play =  false;
        $container.innerFadeTimer = $.fader.next($container);
    };
    
    $.fader.timedChange = function($container){
    	$container.innerFadeTimer = setTimeout(function() {
            $.fader.next($container);
        }, $container.o.timeout);
    };
    
    $.fader.next = function($container) {
    	
    	if($container.currentSlide.slideNumber != $container.nextSlide.slideNumber){
	        if ($container.o.animationType == 'slide') {
	        	$container.currentSlide.slideUp($container.o.speed);
	        	$container.nextSlide.slideDown($container.o.speed);
	        } else if ($container.o.animationType == 'fade') {
	        	$container.currentSlide.fadeOut($container.o.speed);
	        	$container.nextSlide.fadeIn($container.o.speed, function() {
								removeFilter($(this)[0]);
							});
	        } else
	            alert('Innerfade-animationType must either be \'slide\' or \'fade\'');
	        
	        
	        
	        // sequence
	        $container.currentSlide = $container.nextSlide;
	        if($container.currentSlide.slideNumber + 1 > ($container.slides.length-1)){
	        	$container.nextSlide =	$container.slides[0];
	        }else{
	        	$container.nextSlide = $container.slides[$container.currentSlide.slideNumber + 1];
	        }
	        
	        // fade the jumpNav
	        $('.fader-jumpNav .active',$container.parent)
	    		.animate({opacity:0.3},$container.o.speed/2,false,function(){$(this).removeClass('active');})
				.animate({opacity:1},$container.o.speed/2);
	        
	        $container.currentSlide.data('anchor')
	        		.animate({opacity:0.3},$container.o.speed/2,false,function(){$(this).addClass('active');})
    				.animate({opacity:1},$container.o.speed/2);
    	}
    	
        // check if play or not
        if($container.o.play){
        	$.fader.timedChange($container);
        }
    };

})(jQuery);

// **** remove Opacity-Filter in ie ****
function removeFilter(element) {
	if(element.style.removeAttribute){
		element.style.removeAttribute('filter');
	}
}
