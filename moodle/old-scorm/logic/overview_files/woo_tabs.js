jQuery(document).ready(function(){

        // UL = .wooTabs
        // Tab contents = .inside
        
       var tag_cloud_class = '#tagcloud'; 
       
              //Fix for tag clouds - unexpected height before .hide() 
            var tag_cloud_height = jQuery('#tagcloud').height();

       jQuery('.inside ul li:last-child').css('border-bottom','0px') // remove last border-bottom from list in tab conten
       jQuery('.wooTabs').each(function(){
       	jQuery(this).children('li').children('a:first').addClass('selected'); // Add .selected class to first tab on load
       });
       jQuery('.inside > *').hide();
       jQuery('.inside > *:first-child').show();
       

       jQuery('.wooTabs li a').click(function(evt){ // Init Click funtion on Tabs
        
            var clicked_tab_ref = jQuery(this).attr('href'); // Strore Href value
            
            jQuery(this).parent().parent().children('li').children('a').removeClass('selected'); //Remove selected from all tabs
            jQuery(this).addClass('selected');
            jQuery(this).parent().parent().parent().children('.inside').children('*').hide();
            
            /*
            if(clicked_tab_ref === tag_cloud_class) // Initiate tab fix (+20 for padding fix)
            {
                clicked_tab_ref_height = tag_cloud_height + 20;
            }
            else // Other height calculations
            {
                clicked_tab_ref_height = jQuery('.inside ' + clicked_tab_ref).height();
            }
            */
             //jQuery('.inside').stop().animate({
            //    height: clicked_tab_ref_height
            // },400,"linear",function(){
                    //Callback after new tab content's height animation
                    jQuery('.inside ' + clicked_tab_ref).fadeIn(500);
            // })
             
             evt.preventDefault();

        })
    
})