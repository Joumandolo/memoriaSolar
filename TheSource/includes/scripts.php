<?php global $shortname; ?>
	<script type="text/javascript" src="<?php bloginfo('template_directory')?>/js/superfish.js"></script>	
	<script type="text/javascript">
	//<![CDATA[
		jQuery.noConflict();
	
		jQuery('ul.superfish, #page-menu ul.nav').superfish({ 
			delay:       300,                            // one second delay on mouseout 
			animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
			speed:       'fast',                          // faster animation speed 
			autoArrows:  true,                           // disable generation of arrow mark-up 
			dropShadows: false                            // disable drop shadows 
		});
		
		jQuery('ul.nav > li > a.sf-with-ul').parent('li').addClass('sf-ul'); 
		
		et_search_bar();
		et_footer_improvements('#footer .footer-widget');
		
		<!---- et_switcher plugin v1.3 ---->
		(function($)
		{
			$.fn.et_switcher = function(options)
			{
				var defaults =
				{
				   slides: '>div',
				   activeClass: 'active',
				   linksNav: '',
				   findParent: true, //use parent elements in defining lengths
				   lengthElement: 'li', //parent element, used only if findParent is set to true
				   useArrows: false,
				   arrowLeft: 'prevlink',
				   arrowRight: 'nextlink',
				   auto: false,
				   autoSpeed: 5000
				};

				var options = $.extend(defaults, options);

				return this.each(function()
				{
					var slidesContainer = jQuery(this);
					slidesContainer.find(options.slides).hide().end().find(options.slides).filter(':first').css('display','block');
			 
					if (options.linksNav != '') { 
						var linkSwitcher = jQuery(options.linksNav);
										
						linkSwitcher.click(function(){
							var targetElement;

							if (options.findParent) targetElement = jQuery(this).parent();
							else targetElement = jQuery(this);

							if (targetElement.hasClass('active')) return false;

							targetElement.siblings().removeClass('active').end().addClass('active');

							var ordernum = targetElement.prevAll(options.lengthElement).length;
											
							slidesContainer.find(options.slides).filter(':visible').hide().end().end().find(options.slides).filter(':eq('+ordernum+')').stop(true,true).fadeIn(700);
							return false;
						});
					};
					
					jQuery('#'+options.arrowRight+', #'+options.arrowLeft).click(function(){
					  
						var slideActive = slidesContainer.find(options.slides).filter(":visible"),
							nextSlide = slideActive.next(),
							prevSlide = slideActive.prev();

						if (jQuery(this).attr("id") == options.arrowRight) {
							if (nextSlide.length) {
								var ordernum = nextSlide.prevAll().length;                        
							} else { var ordernum = 0; }
						};

						if (jQuery(this).attr("id") == options.arrowLeft) {
							if (prevSlide.length) {
								var ordernum = prevSlide.prevAll().length;                  
							} else { var ordernum = slidesContainer.find(options.slides).length-1; }
						};

						slidesContainer.find(options.slides).filter(':visible').hide().end().end().find(options.slides).filter(':eq('+ordernum+')').stop(true,true).fadeIn(700);

						if (typeof interval != 'undefined') {
							clearInterval(interval);
							auto_rotate();
						};

						return false;
					});   

					if (options.auto) {
						auto_rotate();
					};
					
					function auto_rotate(){
						interval = setInterval(function(){
							var slideActive = slidesContainer.find(options.slides).filter(":visible"),
								nextSlide = slideActive.next();
						 
							if (nextSlide.length) {
								var ordernum = nextSlide.prevAll().length;                        
							} else { var ordernum = 0; }
						 
							if (options.linksNav === '') 
								jQuery('#'+options.arrowRight).trigger("click");
							else 		 		
								linkSwitcher.filter(':eq('+ordernum+')').trigger("click");
						},options.autoSpeed);
					};
				});
			}
		})(jQuery);
		
		
		var $featuredArea = jQuery('#featured'),
			$all_tabs = jQuery('#all_tabs');
		
		jQuery(window).load( function(){
			if ($featuredArea.length) {
				$featuredArea.addClass('et_slider_loaded').et_switcher({
					useArrows: true <?php if (get_option($shortname.'_slider_auto') == 'on') { ?>,
											auto: true,
											autoSpeed: <?php echo esc_js(get_option($shortname.'_slider_autospeed')); ?>
									<?php }; ?>		
				});
				
				if ( $featuredArea.find('.slide').length == 1 ){
					jQuery('#featured-control a#prevlink, #featured-control a#nextlink').hide();
				}
			};
		} );
				
		if ($all_tabs.length) {
			$all_tabs.et_switcher({
				linksNav: 'ul#tab_controls li a'
			});
		}; 

		<?php if (get_option($shortname.'_disable_toptier') == 'on') echo('jQuery("ul.nav > li > ul").prev("a").attr("href","#");'); ?>
		
		<!---- Footer Improvements ---->
		function et_footer_improvements($selector){
			var $footer_widget = jQuery($selector);
		
			if (!($footer_widget.length == 0)) {
				$footer_widget.each(function (index, domEle) {
					if ((index+1)%4 == 0) jQuery(domEle).addClass("last").after("<div class='clear'></div>");
				});
			};
		};
		
		<!---- Search Bar Improvements ---->
		function et_search_bar(){
			var $searchform = jQuery('#cat-nav div#search-form'),
				$searchinput = $searchform.find("input#searchinput"),
				searchvalue = $searchinput.val();
				
			$searchinput.focus(function(){
				if (jQuery(this).val() === searchvalue) jQuery(this).val("");
			}).blur(function(){
				if (jQuery(this).val() === "") jQuery(this).val(searchvalue);
			});
		};
		
	//]]>	
	</script>