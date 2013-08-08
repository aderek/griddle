/* TODO 

breakpoints - DONE

generate - with sample HTML and offsets - DONE

validation
style
fluid/full width?

url variables - share URL

griddle plugin to add to any page - adds in the section underlay, z index on top of everything?

*/

$(window).load(function() {

	$('button#test').click(function(event) {
	
		event.preventDefault();
		
		var grid = $.griddle({
			'grid_width': $('.width_page').val(), 
			'col_num': $('.col_num').val(),
			'col_margin': $('.col_margin').val(),
			'tab_land' : $('#tab_land:checkbox:checked').val(),
			'tab_port' : $('#tab_port:checkbox:checked').val(),
			'phone_land' : $('#phone_land:checkbox:checked').val(),
//			'phone_port' : $('#phone_port').val()
		});
		
		grid.test();
	
	});

});


(function($) {
    $.griddle = function(options) {
        var grid = {
        	options: $.extend({
	            grid_width: '1024px',
	            col_num: '12',
	            col_margin: '10'
	        }, options),
            test: function( ) {
            	
            	grid_width = this.options.grid_width;
            	col_num = this.options.col_num;
            	col_margin = this.options.col_margin;
            
            	underlay = $('.underlay');
            	
            	underlay.empty();
            
            	/* make desktop grid styles */
            
                underlay.css('width', grid_width);
                
                for (i=0;i<col_num;i++) {
                
                	div_html = '<div class="col"></div>';
                	underlay.append(div_html);
                
                }
                
                $('.underlay .col').css('margin', '0 '+col_margin + 'px');
                
                col_width = Math.floor((parseInt(grid_width) / (parseInt(col_num)) - (parseInt(col_margin) * 2)))
                
                $('.underlay .col').css('width', col_width + 'px');
                
                /* end desktop grid styles */
                
                /* do responsive queries */
                
                if (this.options.tab_land) {
                
                	col_width = Math.floor((1024 / (parseInt(col_num)) - (10 * 2)))
                
                	document.querySelector('style').textContent +=
                    	"@media screen and (max-width:1024px) { .underlay { width: 1024px !important; } .underlay .col { margin: 0 10px !important; width: "+col_width+"px !important}}";
                    	
                }
                
                if (this.options.tab_port) {
                
                	col_width = Math.floor((768 / (parseInt(col_num)) - (10 * 2)))
                
                	document.querySelector('style').textContent +=
                    	"@media screen and (max-width:768px) { .underlay { width: 768px !important; } .underlay .col { margin: 0 10px !important; width: "+col_width+"px !important}}";
                    	
                }
                
                if (this.options.phone_land) {
                
                	document.querySelector('style').textContent +=
                    	"@media screen and (max-width:568px) { .underlay { width: 100% !important; } .underlay .col { margin: 5px 0 !important; padding: 0 10px !important; width: 100% !important; clear: left; box-sizing: border-box; -moz-box-sizing: border-box; height: 60px;}}";
                    	
                }
                
                /* end responsive queries */
                underlay.show();
            
            },
            download: function( ) {
            
            },
        };
        return grid;
    };
})(jQuery);