/********************************************************************
 * Tooltips
 * 
 * Author: Nicolas Rod, nico@alaxos.com 
 * website: www.alaxos.com, www.alaxos.ch
 */
var tt_container = '<div id="tt_container"><div id="tt_content"></div></div>';
var tt_content = "";
var tt_hovered_id = "";
var tt_visible_tooltip_id = "";
var tt_contents = new Array();
var tt_timer_show = null;
var tt_timer_hide = null;

var tt_show_delay = 100; 
var tt_show_time  = 400; 
var tt_hide_delay = 250;
var tt_hide_time  = 500;

var tt_correction_time = 300;

function register_tool_tip(element_id, content_url, position)
{
	if(position == null)
	{
		position = "br"; //bottom right
	}
	
	/*
	* Note do not user JQuery hover() function as it doesn't work with FX when you enter/leave from/to a textarea 
	*/
	$j(element_id).bind("mouseover",
	function(){

		tt_hovered_id = element_id;
		show_tooltip(element_id, content_url, position);

	});
	
	$j(element_id).bind("mouseout",
	function(){

		tt_hovered_id = "";
		hide_tooltip(element_id);
		
	});
}

function show_tooltip(element_id, content_url, position)
{
	if(typeof(tt_timer_show) == "object" || tt_timer_show > 0)
	{
		clearTimeout(tt_timer_show);
	}
	
	tt_timer_show = setTimeout(function(){

		if(tt_hovered_id.length > 0)
		{
        	var refresh        = false;
        	var corrected_left = null;
        	var corrected_top  = null;
        	
        	if($j("#tt_container").length == 0)
        	{
        		$j("body").append(tt_container);
        		refresh = true;
        	}
        	
        	if(tt_visible_tooltip_id.length == 0 || (tt_visible_tooltip_id.length > 0 && tt_visible_tooltip_id != element_id))
        	{
        		/*
        		* if we hover another element than the currently displayed tooltip, we set
        		* it display to none to allow viewing fade in
        		*/
        		$j("#tt_container").css("display", "none");
        		refresh = true;
        	}
        	
        	tt_visible_tooltip_id = element_id;
        
        	if(refresh)
        	{
        		tt_content = '<p><img src="' + application_root + 'alaxos/img/ajax/waiting24.gif" /></p>';
        		$j("#tt_content").html(tt_content);
        
        		$j("#tt_container").fadeIn(tt_show_time);
        
        		if(tt_contents[element_id])
        		{
        			$j("#tt_content").html(tt_contents[element_id]);
        		}
        		else
        		{
        			if(content_url.toLowerCase().match("\.(jpg|jpeg|gif|png|bmp)$"))
        			{
        				var data = '<img src="' + content_url + '" alt="" />';
        				$j("#tt_content").html(data);
        				tt_contents[element_id] = data;
        			}
        			else
        			{
	                	$j.ajax({  
	                		type: 'GET',
	                		async: false, //-> get the size of the tooltip before to set its position
	                        url: content_url,  
	                        success: function(data){  
	                
	                				$j("#tt_content").html(data);
	                				tt_contents[element_id] = data;
	                           	},
	                        error: function(request, textStatus, error){
	                
	                        	   $j("#tt_content").html(error);
	                        	   
	                	       	}
	                       });
        			}
        		}
        		
        		/*
        		 * Since the content of the tooltip is set, it has its definitive dimension
        		 * -> we now calculate its position according to its width and height.
        		 * Note: 
        		 * 	with Mozilla family browsers, we could use right and bottom CSS properties to position the 
        		 * 	tooltip when it is not positionned bottom-right, but it doesn't work with IE
        		 * 	-> we fill the tooltip content before to position it
        		 * 		-> it has a size
        		 * 			-> we can calculate its position with top and left has we have a height and width 
        		 */
        		var tt_width       = $j("#tt_container").width();
        		var tt_height      = $j("#tt_container").height();
        		
        		var element_pos    = $j(element_id).offset();
    			var element_left   = Math.round(element_pos.left);
        		var element_top    = Math.round(element_pos.top);
        		
        		var element_width  = $j(element_id).width();
        		var element_height = $j(element_id).height();
        		
        		var padding_left   = $j("#tt_container").css("padding-left").replace("px", "");
    			var padding_right  = $j("#tt_container").css("padding-right").replace("px", "");
    			var padding_top    = $j("#tt_container").css("padding-top").replace("px", "");
    			var padding_bottom = $j("#tt_container").css("padding-bottom").replace("px", "");
    			
    			var corrected_left  = null;
    			var corrected_right = null;
    			
        		if(position == "bl") //bottom left
        		{	
        			var left = element_left - tt_width - padding_left - padding_right;
            		var top  = element_top  + element_height;
            		
            		/*
            		 * Calculate an eventual new position if the tooltip is outside of the visible area
            		 */
            		if(left < $j(window).scrollLeft())
        			{
            			corrected_left = $j(window).scrollLeft() + 5;
        			}
            		
            		if((top + tt_height) > ($j(window).height() + $j(window).scrollTop()))
        			{
            			corrected_top = $j(window).height() - tt_height - padding_bottom - padding_top - 5 + $j(window).scrollTop();
        			}
        		}
        		else if(position == "tl") //top left
        		{
        			var left = element_left - tt_width - padding_left - padding_right;
            		var top  = element_top  - tt_height - padding_top - padding_bottom;
            		
            		/*
            		 * Calculate an eventual new position if the tooltip is outside of the visible area
            		 */
            		if(left < $j(window).scrollLeft())
        			{
            			corrected_left = $j(window).scrollLeft() + 5;
        			}
            		
            		if(top < $j(window).scrollTop())
        			{
            			corrected_top = $j(window).scrollTop() + 5;
        			}
        		}
        		else if(position == "tr") //top right
        		{	
        			var left = element_left + element_width;
            		var top  = element_top  - tt_height - padding_top - padding_bottom;
            		
            		/*
            		 * Calculate an eventual new position if the tooltip is outside of the visible area
            		 */
            		if((left + tt_width) > ($j(window).width() + $j(window).scrollLeft()))
        			{
            			corrected_left = left - ((left + tt_width) - $j(window).width()) - padding_left - padding_right - 5 + $j(window).scrollLeft();
        			}
            		
            		if(top < $j(window).scrollTop())
        			{
            			corrected_top = $j(window).scrollTop() + 5;
        			}
        		}
        		else if(position == "centered") //tooltip centered hover the element
        		{
        			var padding_horizontal = (parseInt(padding_left) + parseInt(padding_right)) / 2;
        			var padding_vertical = (parseInt(padding_top) + parseInt(padding_bottom)) / 2;
        			
					var left = element_left + (element_width/2)  - (tt_width/2) - padding_horizontal;
					var top  = element_top  + (element_height/2) - (tt_height/2) - padding_vertical;
				}
        		else	// default is bottom right ("br")
        		{
        			var left = element_left + element_width;
            		var top  = element_top  + element_height;
            		
            		/*
            		 * Calculate an eventual new position if the tooltip is outside of the visible area
            		 */
            		if((left + tt_width) > ($j(window).width() + $j(window).scrollLeft()))
        			{
            			corrected_left = left - ((left + tt_width) - $j(window).width()) - padding_left - padding_right - 5 + $j(window).scrollLeft();
        			}
            		
            		if((top + tt_height) > ($j(window).height() + $j(window).scrollTop()))
        			{
            			corrected_top = $j(window).height() - tt_height - padding_bottom - padding_top - 5 + $j(window).scrollTop();
        			}
        		}
        		
        		$j("#tt_container").css("left", left + "px");
        		$j("#tt_container").css("top",  top  + "px");
        		
            	register_tt_container_events(element_id, left, top, corrected_left, corrected_top);
        	}
		}
	}, tt_show_delay);
}

function register_tt_container_events(element_id, left, top, corrected_left, corrected_top)
{
	//alert(corrected_left + "x" + corrected_top);
	
	if(corrected_left != null && corrected_top != null)
	{
		$j("#tt_container").animate({"left": "+=" + (corrected_left - left) + "px", "top": "+=" + (corrected_top - top) + "px"}, tt_correction_time);
	}
	else if(corrected_left != null)
	{
		$j("#tt_container").animate({"left": "+=" + (corrected_left - left) + "px"}, tt_correction_time);
	}
	else if(corrected_top != null)
	{
		$j("#tt_container").animate({"top": "+=" + (corrected_top - top) + "px"}, tt_correction_time);
	}
	
	/*
	* Note do not user JQuery hover() function as it doesn't with FX when you enter/leave from/to a textarea 
	*/
	$j("#tt_container").bind("mouseover",
			function(){

				tt_hovered_id = element_id;

			});
	$j("#tt_container").bind("mouseout",
			function(){

				tt_hovered_id = "";
				hide_tooltip(element_id)
				
			});
	
}

function hide_tooltip(element_id)
{
	if(typeof(tt_timer_hide) == "object" || tt_timer_hide > 0)
	{
		clearTimeout(tt_timer_hide);
	}
	
	if(tt_visible_tooltip_id.length == 0 || tt_visible_tooltip_id == element_id)
	{	
		tt_timer_hide = setTimeout(function(){

			if(tt_hovered_id == "")
			{
    			$j("#tt_container").fadeOut(tt_hide_time);
    			tt_visible_tooltip_id = "";
			}
		
		}, tt_hide_delay);
	}
	else if(tt_visible_tooltip_id != element_id)
	{
		tt_timer_hide = setTimeout(function(){

			if(tt_visible_tooltip_id != "" && tt_visible_tooltip_id != element_id)
			{
        		/*
        		* Another tooltip than the current displayed has to be shown
        		* => we hide the currently displayed one without fading it out
        		*/
        		$j("#tt_container").remove();
        		tt_visible_tooltip_id = "";
			}
			
		}, tt_hide_delay);
	}
}