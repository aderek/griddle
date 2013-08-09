<?php 

	// get the posted data

	if(isset($_POST['col_num']) && isset($_POST['col_margin']) && isset($_POST['width_page'])) {

		// CREATE THE CSS FILE

		$grid_width = $_POST['width_page'];
		$col_num = $_POST['col_num'];
		$col_margin = $_POST['col_margin'];
	
		// calculate the actual width of each column
		
		$col_width = floor((intval($grid_width) / (intval($col_num)) - (intval($col_margin) * 2)));
	
		// for each # of columns, make the appropriate CSS
	
		$str_css = '';
		
	    $str_css .= generate_css($col_width, $col_margin, $col_num);
	    
	    // now do responsive breakpoints
	    
	    if (isset($_POST['tab_land'])) {
	    
	    	$col_width = floor((1024 / (intval($col_num)) - (10 * 2)));
	    
	    	$str_css .= "@media screen and (max-width:1024px) {";
	    
	    	$str_css .= generate_css($col_width, 10, $col_num);
	    	
	    	$str_css .= "}";
	        	
	    }
	    
	    if (isset($_POST['tab_port'])) {
	    
	    	$col_width = floor((768 / (intval($col_num)) - (10 * 2)));
    	
    		$str_css .= "@media screen and (max-width:768px) {";
    	
    		$str_css .= generate_css($col_width, 10, $col_num);
    		
    		$str_css .= "}";
	        	
	    }
	    
	    if (isset($_POST['phone_land'])) {
    	
    		$str_css .= "@media screen and (max-width:568px) {";
    	
    		$str_css .= generate_css($col_width, 0, $col_num, True);
    		
    		$str_css .= "}";
	        	
	    }
	    
	    // save to file
	    
	    ob_start();
	    
	    echo $str_css;
	    
	    $css = ob_get_clean();
	    
	    $file_name = 'tmp/griddle'.time().'.css';
	    
		file_put_contents($file_name, $css, LOCK_EX);
		
		// now download the file
		
		if(is_file($file_name)) {
		
			// required for IE
			if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}
		
			// get the file mime type using the file extension
			switch(strtolower(substr(strrchr($file_name,'.'),1)))
			{
				case 'pdf': $mime = 'application/pdf'; break;
				case 'zip': $mime = 'application/zip'; break;
				case 'jpeg':
				case 'jpg': $mime = 'image/jpg'; break;
				default: $mime = 'application/force-download';
			}
			header('Pragma: public'); 	// required
			header('Expires: 0');		// no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private',false);
			header('Content-Type: '.$mime);
			header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.filesize($file_name));	// provide file size
			readfile($file_name);		// push it out
			
		}
		
		// now delete the file
		unlink($file_name);
		
	}
	else {
	
		// the form variables weren't correctly filled in

		return False;
		
	}
	
	
	/* generates the actual CSS
	*	@args width of the column, the margin (defaults to 10) and the number of the columns
	* 	@return the string CSS
	*/
	
	function generate_css($col_width, $col_margin, $col_num, $mobile=False) {
	
		$str_css = '';
		
		for ($i=1;$i<=$col_num;$i++) {
		
			if(!$mobile) {
		
				$str_css .= '.span'.$i.'{width:'.(($col_width * $i) + (($i-1) * ($col_margin*2))).'px; margin:0 '.$col_margin.'px;float:left;}';
				
			}
			
			else {
			
				$str_css .= '.span'.$i.'{width:100%; margin:0; padding: 0 10px; clear: left; box-sizing: border-box; -moz-box-sizing: border-box;}';
				
			}
				
		}
		
		for ($i=1;$i<=$col_num;$i++) {
		
		  if(!$mobile) {

	  		// do the suffix classes (margin-right)
	  		$offset = ($col_width * $i) + (($col_margin*2) * ($i-1)) + $col_margin;
	  		
	  		$str_css .= '.suffix'.$i.'{margin-right:'.$offset .'px;}';
	  		
	  		// do the prefix classes (margin-left)
	  		$str_css .= '.prefix'.$i.'{margin-left:'.$offset .'px;}';
	  	
	  	}
	  	
	  	else {
	  	
	  		$str_css .= '.prefix'.$i.'{margin: 0}';
	  		
	  		$str_css .= '.suffix'.$i.'{margin: 0}';
	  		
	  	}
		
		}
		
		return $str_css;
	
	}


?>