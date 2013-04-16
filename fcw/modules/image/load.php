<?php

$image_and_font = new image_and_font;

class image_and_font{

	public $url_character_limit;
	
	function image_and_font(){
	
		$this->__construct();
	
	}
	
	function __construct(){
	
			$this->url_character_limit = 1500;
	}
	
	function create_image($path, $text, $options, $font = '', $sec_text = '', $save = false, $save_path = ''){
	
		if($font == ''){
			$font = 'AllertaStencil-Regular.ttf';
		}
		
		$all_fonts = $this->all_fonts();
		
		if(in_array(urldecode($font), $all_fonts)){
			
			$font = urldecode($font);
			
		}else{
			
			$font = $this->get_font(urldecode($font));
			
		}
	
		$rImg = imagecreatefromstring(file_get_contents($path));
		
		$color = imagecolorallocate($rImg, $options['r'], $options['g'], $options['b']);
		
		if($color == FALSE){
			
			die('Failed to allocate color ' . $options['r'] . ' ' . $options['g'] . ' ' . $options['b']); exit;
			
		}
		
		$font = plugin_dir_path( __FILE__ ).'fonts/'.$font;
		
		$ox = imagesx($rImg);
		$oy = imagesy($rImg);
		
		$x_ratio = 1;
			
		$y_ratio = 1;
		
		if($options['width'] != '' && $options['height'] != ''){
		
			$rImg_o = $rImg;
		
			$height = $options['height'];
			$width = $options['width'];
			
			if($ox < $oy)   #portrate
			    {
			       $ny = $height;
			       $nx = floor($ox * ($ny / $oy)); 
			    } 
			else #landscape
			    {
			       $nx = $width;
			       $ny = floor($oy * ($nx / $ox)); 
			    } 
		
			$rImg = imagecreatetruecolor($nx, $ny);
			
			imagecopyresized($rImg, $rImg_o, 0, 0, 0, 0, $nx, $ny, $ox, $oy);
			
			$x_ratio = $nx / $ox;
			
			$y_ratio = $ny / $oy;
			
		}else{
			
			$rImg_o = $rImg;
			
		}
		
		if($options['center'] == 'yes'){
		
			$tb = imagettfbbox($options['font_size'], $options['angle'], $font, urldecode($text));
		
			$x = ceil(($ox - $tb[2]) / 2);
		
			$y = ceil(($oy - $tb[3]) / 2);
		
		}else{
			
			$x = $options['left']*$x_ratio;
		
			$y = $options['top']*$y_ratio;
			
		}
		
		$options['font_size'] = $options['font_size']*$x_ratio;
		
		imagettftext( $rImg, $options['font_size'], $options['angle'], $x, $y, $color, $font, urldecode($text) );
		
		if($sec_text != ''){
		
			$color = imagecolorallocate($rImg, 255, 0, 0);
		
			$tb = imagettfbbox(40, 0, $font, urldecode($sec_text));
						
			$font = plugin_dir_path( __FILE__ ).'fonts/AllertaStencil-Regular.ttf';
		
			$x = ceil(($ox - $tb[2]) / 2);
		
			$y = ceil(($oy - $tb[3]) / 2);
			
			imagettftext( $rImg, 40, 0, $x, $y, $color, $font, urldecode($sec_text ));
			
		}

		header('Content-type: image/png');
	
		imagepng($rImg);
	
		imagedestroy($rImg);
		
		die();

	}
	
	/* 	returns image resource resized as specified */
	function resize_image($file, $w, $h, $crop=FALSE) {
	    list($width, $height) = getimagesize($file);
	    $r = $width / $height;
	    if ($crop) {
	        if ($width > $height) {
	            $width = ceil($width-($width*($r-$w/$h)));
	        } else {
	            $height = ceil($height-($height*($r-$w/$h)));
	        }
	        $newwidth = $w;
	        $newheight = $h;
	    } else {
	        if ($w/$h > $r) {
	            $newwidth = $h*$r;
	            $newheight = $h;
	        } else {
	            $newheight = $w/$r;
	            $newwidth = $w;
	        }
	    }
	    $src = imagecreatefromjpeg($file);
	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
	    return $dst;
	}
	
	function rgb2hex($rgb) {
	   $hex = "#";
	   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
	
	   return $hex; // returns the hex value including the number sign (#)
	}
	
	/* Returns an array of array('r'=>'0', 'g'=>'0', 'b'=>'0') for given HTML color name */
	function color_name_to_rgb($color_name){
	    // standard 147 HTML color names
	    $colors  =  array(
	        'aliceblue'=>'F0F8FF',
	        'antiquewhite'=>'FAEBD7',
	        'aqua'=>'00FFFF',
	        'aquamarine'=>'7FFFD4',
	        'azure'=>'F0FFFF',
	        'beige'=>'F5F5DC',
	        'bisque'=>'FFE4C4',
	        'black'=>'000000',
	        'blanchedalmond '=>'FFEBCD',
	        'blue'=>'0000FF',
	        'blueviolet'=>'8A2BE2',
	        'brown'=>'A52A2A',
	        'burlywood'=>'DEB887',
	        'cadetblue'=>'5F9EA0',
	        'chartreuse'=>'7FFF00',
	        'chocolate'=>'D2691E',
	        'coral'=>'FF7F50',
	        'cornflowerblue'=>'6495ED',
	        'cornsilk'=>'FFF8DC',
	        'crimson'=>'DC143C',
	        'cyan'=>'00FFFF',
	        'darkblue'=>'00008B',
	        'darkcyan'=>'008B8B',
	        'darkgoldenrod'=>'B8860B',
	        'darkgray'=>'A9A9A9',
	        'darkgreen'=>'006400',
	        'darkgrey'=>'A9A9A9',
	        'darkkhaki'=>'BDB76B',
	        'darkmagenta'=>'8B008B',
	        'darkolivegreen'=>'556B2F',
	        'darkorange'=>'FF8C00',
	        'darkorchid'=>'9932CC',
	        'darkred'=>'8B0000',
	        'darksalmon'=>'E9967A',
	        'darkseagreen'=>'8FBC8F',
	        'darkslateblue'=>'483D8B',
	        'darkslategray'=>'2F4F4F',
	        'darkslategrey'=>'2F4F4F',
	        'darkturquoise'=>'00CED1',
	        'darkviolet'=>'9400D3',
	        'deeppink'=>'FF1493',
	        'deepskyblue'=>'00BFFF',
	        'dimgray'=>'696969',
	        'dimgrey'=>'696969',
	        'dodgerblue'=>'1E90FF',
	        'firebrick'=>'B22222',
	        'floralwhite'=>'FFFAF0',
	        'forestgreen'=>'228B22',
	        'fuchsia'=>'FF00FF',
	        'gainsboro'=>'DCDCDC',
	        'ghostwhite'=>'F8F8FF',
	        'gold'=>'FFD700',
	        'goldenrod'=>'DAA520',
	        'gray'=>'808080',
	        'green'=>'008000',
	        'greenyellow'=>'ADFF2F',
	        'grey'=>'808080',
	        'honeydew'=>'F0FFF0',
	        'hotpink'=>'FF69B4',
	        'indianred'=>'CD5C5C',
	        'indigo'=>'4B0082',
	        'ivory'=>'FFFFF0',
	        'khaki'=>'F0E68C',
	        'lavender'=>'E6E6FA',
	        'lavenderblush'=>'FFF0F5',
	        'lawngreen'=>'7CFC00',
	        'lemonchiffon'=>'FFFACD',
	        'lightblue'=>'ADD8E6',
	        'lightcoral'=>'F08080',
	        'lightcyan'=>'E0FFFF',
	        'lightgoldenrodyellow'=>'FAFAD2',
	        'lightgray'=>'D3D3D3',
	        'lightgreen'=>'90EE90',
	        'lightgrey'=>'D3D3D3',
	        'lightpink'=>'FFB6C1',
	        'lightsalmon'=>'FFA07A',
	        'lightseagreen'=>'20B2AA',
	        'lightskyblue'=>'87CEFA',
	        'lightslategray'=>'778899',
	        'lightslategrey'=>'778899',
	        'lightsteelblue'=>'B0C4DE',
	        'lightyellow'=>'FFFFE0',
	        'lime'=>'00FF00',
	        'limegreen'=>'32CD32',
	        'linen'=>'FAF0E6',
	        'magenta'=>'FF00FF',
	        'maroon'=>'800000',
	        'mediumaquamarine'=>'66CDAA',
	        'mediumblue'=>'0000CD',
	        'mediumorchid'=>'BA55D3',
	        'mediumpurple'=>'9370D0',
	        'mediumseagreen'=>'3CB371',
	        'mediumslateblue'=>'7B68EE',
	        'mediumspringgreen'=>'00FA9A',
	        'mediumturquoise'=>'48D1CC',
	        'mediumvioletred'=>'C71585',
	        'midnightblue'=>'191970',
	        'mintcream'=>'F5FFFA',
	        'mistyrose'=>'FFE4E1',
	        'moccasin'=>'FFE4B5',
	        'navajowhite'=>'FFDEAD',
	        'navy'=>'000080',
	        'oldlace'=>'FDF5E6',
	        'olive'=>'808000',
	        'olivedrab'=>'6B8E23',
	        'orange'=>'FFA500',
	        'orangered'=>'FF4500',
	        'orchid'=>'DA70D6',
	        'palegoldenrod'=>'EEE8AA',
	        'palegreen'=>'98FB98',
	        'paleturquoise'=>'AFEEEE',
	        'palevioletred'=>'DB7093',
	        'papayawhip'=>'FFEFD5',
	        'peachpuff'=>'FFDAB9',
	        'peru'=>'CD853F',
	        'pink'=>'FFC0CB',
	        'plum'=>'DDA0DD',
	        'powderblue'=>'B0E0E6',
	        'purple'=>'800080',
	        'red'=>'FF0000',
	        'rosybrown'=>'BC8F8F',
	        'royalblue'=>'4169E1',
	        'saddlebrown'=>'8B4513',
	        'salmon'=>'FA8072',
	        'sandybrown'=>'F4A460',
	        'seagreen'=>'2E8B57',
	        'seashell'=>'FFF5EE',
	        'sienna'=>'A0522D',
	        'silver'=>'C0C0C0',
	        'skyblue'=>'87CEEB',
	        'slateblue'=>'6A5ACD',
	        'slategray'=>'708090',
	        'slategrey'=>'708090',
	        'snow'=>'FFFAFA',
	        'springgreen'=>'00FF7F',
	        'steelblue'=>'4682B4',
	        'tan'=>'D2B48C',
	        'teal'=>'008080',
	        'thistle'=>'D8BFD8',
	        'tomato'=>'FF6347',
	        'turquoise'=>'40E0D0',
	        'violet'=>'EE82EE',
	        'wheat'=>'F5DEB3',
	        'white'=>'FFFFFF',
	        'whitesmoke'=>'F5F5F5',
	        'yellow'=>'FFFF00',
	        'yellowgreen'=>'9ACD32');
	
	    $color_name = strtolower($color_name);
	    if (isset($colors[$color_name]))
	    {
	        if(strlen($colors[$color_name]) == 3) {
		      $r = hexdec(substr($colors[$color_name],0,1).substr($colors[$color_name],0,1));
		      $g = hexdec(substr($colors[$color_name],1,1).substr($colors[$color_name],1,1));
		      $b = hexdec(substr($colors[$color_name],2,1).substr($colors[$color_name],2,1));
		   } else {
		      $r = hexdec(substr($colors[$color_name],0,2));
		      $g = hexdec(substr($colors[$color_name],2,2));
		      $b = hexdec(substr($colors[$color_name],4,2));
		   }
		   $rgb = array('r' =>$r, 'g' => $g, 'b' => $b);
		   //return implode(",", $rgb); // returns the rgb values separated by commas
		   return $rgb;
	    }
	    else
	    {
	        return ($color_name);
	    }
	}
	
	/* 	returns a <style> link for specified or all fonts */
	function css_link($fonts=''){
	
		$all_fonts = $this->all_fonts();
		
		$link = '';
		
		$current = '';
		
		foreach($all_fonts as $name => $font){
			
			if($fonts == '' || in_array($name, $fonts)){
			
				$font_name = str_replace(array('-Regular','-', ' '), array('', '+', '+'), $name);
				
				$font_name = substr(preg_replace('/(?<!\ )[A-Z]/', '+$0', $font_name).'|', 1);

				if(strlen($current.$font_name) > $this->url_character_limit){
					
					$link .= '~~BREAK~~'.$font_name;
					
					$current = '';
					
				}else{
					
					$link .= $font_name;
					
					$current .= $font_name;
					
				}
				
			}
			
		}
		
		//echo $link;
		
		$breaks = explode('~~BREAK~~', $link);
		
		$return = '';
		
		foreach($breaks as $break){
			
			$return .= "<link href='http://fonts.googleapis.com/css?family=$break' rel='stylesheet' type='text/css'>";
		}
		
		return $return;
		
	}
	
	/* 	returns font filename if exists */
	function get_font($fontname){
		
		$allfonts = $this->all_fonts();
		
		$alt = str_replace(' ', '', $fontname).'-Regular';
		
		if(isset($allfonts[$fontname])){
			return $allfonts[$fontname];
		}elseif(isset($allfonts[$alt])){
			return $allfonts[$alt];
		}else{
			return 'ERROR: Font does not exist.';
		}
		
	}
	
	function all_fonts_select($fonts=''){
		
		$all_fonts = $this->all_fonts();
		
		$font_name = str_replace(array('-Regular','-', ' '), array('', '+', '+'), array_shift(array_keys($all_fonts)));
				
		$font_name = substr(preg_replace('/(?<!\ )[A-Z]/', ' $0', $font_name), 1);
		
		$select .= '<div id="fontSelect"><span>'.$font_name.'</span><div class="arrow-down"></div><ul>';
		
		$hidden = '<div style="visibility:hidden; height: 0px;width: 0px; overflow: hidden;">';
		
		foreach($all_fonts as $name => $font){
			
			if($fonts == '' || in_array($name, $fonts)){
			
				$font_name = str_replace(array('-Regular','-', ' '), array('', '+', '+'), $name);
				
				$font_name = substr(preg_replace('/(?<!\ )[A-Z]/', ' $0', $font_name), 1);
					
				$select .= '<li>'.$font_name.'</li>';
				
				$hidden .= '<p style="font-family:'.$font_name.'">'.$font_name.'</p>';
				
			}
			
		}
		
		$hidden .= '</div>';
		
		$select .= '</ul></div>';
		
		$select .= $this->add_fontSelector_assets($hidden);
		
		return $select;
			
	}
	
	function add_fontSelector_assets($extra = ''){
	
		global $paths;
		
		return "
			$extra
			<link href='".$paths['url'].$paths['fcw_path']."/modules/image/css/style.css' rel='stylesheet' type='text/css'>
			<script>
				
				jQuery(document).ready(function(){
				
				(function( $ ) {

				  var settings;
				
				  var methods = {
				    init : function(options) {
				
				      settings = $.extend( {
				        'hide_fallbacks' : false,
				        'selected' : function(style) {},
				        'initial' : ''
				      }, options);
				
				      var root = this;
				      var ul = this.find('ul');
				      ul.hide();
				      var visible = false;
				
				      if (settings['initial'] != '')
				      {
				        if (settings['hide_fallbacks'])
				          root.find('span').html(settings['initial'].substr(0, settings['initial'].indexOf(',')));
				        else
				          root.find('span').html(settings['initial']);
				
				        root.css('font-family', settings['initial']);
				      }
				
				      ul.find('li').each(function() {
				        $(this).css(\"font-family\", $(this).text());
				
				        if (settings['hide_fallbacks'])
				        {
				          var content = $(this).text();
				          $(this).text(content.substr(0, content.indexOf(',')));
				        }
				      });
				
				      ul.find('li').click(function() {
				
				        if (!visible)
				          return;
				
				        ul.slideUp('fast', function() {
				          visible = false;
				        });
				
				        root.find('span').html( $(this).text() );
				        root.css('font-family', $(this).css('font-family'));
				
				        settings['selected']($(this).css('font-family'));
				      });
				
				      $(this).click(function(event) {
				
				        if (visible)
				          return;
				
				        event.stopPropagation();
				
				        ul.slideDown('fast', function() {
				          visible = true;
				        });
				      });
				
				      $('html').click(function() {
				        if (visible)
				        {
				          ul.slideUp('fast', function() {
				            visible = false;
				          });
				        }
				      })
				    },
				    selected : function() {
				      return this.css('font-family');
				    }
				  };
				
				  $.fn.fontSelector = function(method) {
				    if ( methods[method] ) {
				      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
				    } else if ( typeof method === 'object' || ! method ) {
				      return methods.init.apply( this, arguments );
				    } else {
				      $.error( 'Method ' +  method + ' does not exist on jQuery.fontSelector' );
				    }
				  }
				}) ( jQuery );
				
				jQuery('#fontSelect').fontSelector({
					'selected' : function(style){
						jQuery('#the_text').css('font-family', style);
					}
				});

				});
				
			</script>
		";
		
	}
	
	/* Returns an array of all fonts as 'Font Name' => 'font_file' => '	/* Returns an array of all fonts as 'Font Name' => 'font_file.ttf',' 	 */
	function all_fonts(){
		
		return array(
			'ABeeZee-Regular' => 'ABeeZee-Regular.ttf',
			'Abel-Regular' => 'Abel-Regular.ttf',
			'AbrilFatface-Regular' => 'AbrilFatface-Regular.ttf',
			'Acme-Regular' => 'Acme-Regular.ttf',
			'Actor-Regular' => 'Actor-Regular.ttf',
			'Adamina-Regular' => 'Adamina-Regular.ttf',
			'AdventPro-Regular' => 'AdventPro-Regular.ttf',
			'AguafinaScript-Regular' => 'AguafinaScript-Regular.ttf',
			'Akronim-Regular' => 'Akronim-Regular.ttf',
			'AksaraBaliGalang-Regular' => 'AksaraBaliGalang-Regular.ttf',
			'Aladin-Regular' => 'Aladin-Regular.ttf',
			'Aldrich-Regular' => 'Aldrich-Regular.ttf',
			'Alegreya-Regular' => 'Alegreya-Regular.ttf',
			'AlegreyaSC-Regular' => 'AlegreyaSC-Regular.ttf',
			'AlexBrush-Regular' => 'AlexBrush-Regular.ttf',
			'AlfaSlabOne-Regular' => 'AlfaSlabOne-Regular.ttf',
			'Alice-Regular' => 'Alice-Regular.ttf',
			'Alike-Regular' => 'Alike-Regular.ttf',
			'AlikeAngular-Regular' => 'AlikeAngular-Regular.ttf',
			'Allan-Regular' => 'Allan-Regular.ttf',
			'Allerta-Regular' => 'Allerta-Regular.ttf',
			'AllertaStencil-Regular' => 'AllertaStencil-Regular.ttf',
			'Allura-Regular' => 'Allura-Regular.ttf',
			'Almendra-Regular' => 'Almendra-Regular.ttf',
			'AlmendraDisplay-Regular' => 'AlmendraDisplay-Regular.ttf',
			'AlmendraSC-Regular' => 'AlmendraSC-Regular.ttf',
			'Amarante-Regular' => 'Amarante-Regular.ttf',
			'Amaranth-Regular' => 'Amaranth-Regular.ttf',
			'AmaticSC-Regular' => 'AmaticSC-Regular.ttf',
			'Amethysta-Regular' => 'Amethysta-Regular.ttf',
			'Amiri-Regular' => 'Amiri-Regular.ttf',
			'Anaheim-Regular' => 'Anaheim-Regular.ttf',
			'Andada-Regular' => 'Andada-Regular.ttf',
			'AndadaSC-Regular' => 'AndadaSC-Regular.ttf',
			'AnonymousPro-Regular' => 'AnonymousPro-Regular.ttf',
			'Antic-Regular' => 'Antic-Regular.ttf',
			'AnticDidone-Regular' => 'AnticDidone-Regular.ttf',
			'AnticSlab-Regular' => 'AnticSlab-Regular.ttf',
			'Arapey-Regular' => 'Arapey-Regular.ttf',
			'Arbutus-Regular' => 'Arbutus-Regular.ttf',
			'ArbutusSlab-Regular' => 'ArbutusSlab-Regular.ttf',
			'ArchivoBlack-Regular' => 'ArchivoBlack-Regular.ttf',
			'ArchivoNarrow-Regular' => 'ArchivoNarrow-Regular.ttf',
			'Arimo-Regular' => 'Arimo-Regular.ttf',
			'Arizonia-Regular' => 'Arizonia-Regular.ttf',
			'Armata-Regular' => 'Armata-Regular.ttf',
			'Artifika-Regular' => 'Artifika-Regular.ttf',
			'Arvo-Regular' => 'Arvo-Regular.ttf',
			'Asap-Regular' => 'Asap-Regular.ttf',
			'Astloch-Regular' => 'Astloch-Regular.ttf',
			'Asul-Regular' => 'Asul-Regular.ttf',
			'AtomicAge-Regular' => 'AtomicAge-Regular.ttf',
			'Aubrey-Regular' => 'Aubrey-Regular.ttf',
			'Audiowide-Regular' => 'Audiowide-Regular.ttf',
			'AutourOne-Regular' => 'AutourOne-Regular.ttf',
			'Average-Regular' => 'Average-Regular.ttf',
			'AverageSans-Regular' => 'AverageSans-Regular.ttf',
			'AveriaGruesaLibre-Regular' => 'AveriaGruesaLibre-Regular.ttf',
			'AveriaLibre-Regular' => 'AveriaLibre-Regular.ttf',
			'AveriaSansLibre-Regular' => 'AveriaSansLibre-Regular.ttf',
			'AveriaSerifLibre-Regular' => 'AveriaSerifLibre-Regular.ttf',
			'BadScript-Regular' => 'BadScript-Regular.ttf',
			'Balthazar-Regular' => 'Balthazar-Regular.ttf',
			'Basic-Regular' => 'Basic-Regular.ttf',
			'Battambang-Regular' => 'Battambang-Regular.ttf',
			'Baumans-Regular' => 'Baumans-Regular.ttf',
			'Belgrano-Regular' => 'Belgrano-Regular.ttf',
			'Belleza-Regular' => 'Belleza-Regular.ttf',
			'BenchNine-Regular' => 'BenchNine-Regular.ttf',
			'Bentham-Regular' => 'Bentham-Regular.ttf',
			'BerkshireSwash-Regular' => 'BerkshireSwash-Regular.ttf',
			'BigelowRules-Regular' => 'BigelowRules-Regular.ttf',
			'Bilbo-Regular' => 'Bilbo-Regular.ttf',
			'BilboSwashCaps-Regular' => 'BilboSwashCaps-Regular.ttf',
			'Bitter-Regular' => 'Bitter-Regular.ttf',
			'BlackOpsOne-Regular' => 'BlackOpsOne-Regular.ttf',
			'Bonbon-Regular' => 'Bonbon-Regular.ttf',
			'Boogaloo-Regular' => 'Boogaloo-Regular.ttf',
			'BowlbyOne-Regular' => 'BowlbyOne-Regular.ttf',
			'BowlbyOneSC-Regular' => 'BowlbyOneSC-Regular.ttf',
			'Brawler-Regular' => 'Brawler-Regular.ttf',
			'BreeSerif-Regular' => 'BreeSerif-Regular.ttf',
			'BrunoAce-Regular' => 'BrunoAce-Regular.ttf',
			'BrunoAceSC-Regular' => 'BrunoAceSC-Regular.ttf',
			'BubblegumSans-Regular' => 'BubblegumSans-Regular.ttf',
			'BubblerOne-Regular' => 'BubblerOne-Regular.ttf',
			'Buenard-Regular' => 'Buenard-Regular.ttf',
			'Butcherman-Regular' => 'Butcherman-Regular.ttf',
			'ButchermanCaps-Regular' => 'ButchermanCaps-Regular.ttf',
			'ButterflyKids-Regular' => 'ButterflyKids-Regular.ttf',
			'Cabin-Regular' => 'Cabin-Regular.ttf',
			'CabinCondensed-Regular' => 'CabinCondensed-Regular.ttf',
			'CabinSketch-Regular' => 'CabinSketch-Regular.ttf',
			'CaesarDressing-Regular' => 'CaesarDressing-Regular.ttf',
			'Cagliostro-Regular' => 'Cagliostro-Regular.ttf',
			'Calligraffitti-Regular' => 'Calligraffitti-Regular.ttf',
			'Cambo-Regular' => 'Cambo-Regular.ttf',
			'Cantarell-Regular' => 'Cantarell-Regular.ttf',
			'CantataOne-Regular' => 'CantataOne-Regular.ttf',
			'CantoraOne-Regular' => 'CantoraOne-Regular.ttf',
			'Capriola-Regular' => 'Capriola-Regular.ttf',
			'Cardo-Regular' => 'Cardo-Regular.ttf',
			'Carme-Regular' => 'Carme-Regular.ttf',
			'CarroisGothic-Regular' => 'CarroisGothic-Regular.ttf',
			'CarroisGothicSC-Regular' => 'CarroisGothicSC-Regular.ttf',
			'Caudex-Regular' => 'Caudex-Regular.ttf',
			'CevicheOne-Regular' => 'CevicheOne-Regular.ttf',
			'Changa-Regular' => 'Changa-Regular.ttf',
			'ChangaOne-Regular' => 'ChangaOne-Regular.ttf',
			'Chango-Regular' => 'Chango-Regular.ttf',
			'ChauPhilomeneOne-Regular' => 'ChauPhilomeneOne-Regular.ttf',
			'ChelaOne-Regular' => 'ChelaOne-Regular.ttf',
			'ChelseaMarket-Regular' => 'ChelseaMarket-Regular.ttf',
			'CherrySwash-Regular' => 'CherrySwash-Regular.ttf',
			'Chicle-Regular' => 'Chicle-Regular.ttf',
			'Chivo-Regular' => 'Chivo-Regular.ttf',
			'Cinzel-Regular' => 'Cinzel-Regular.ttf',
			'CinzelDecorative-Regular' => 'CinzelDecorative-Regular.ttf',
			'Clara-Regular' => 'Clara-Regular.ttf',
			'ClickerScript-Regular' => 'ClickerScript-Regular.ttf',
			'Coda-Regular' => 'Coda-Regular.ttf',
			'Codystar-Regular' => 'Codystar-Regular.ttf',
			'Combo-Regular' => 'Combo-Regular.ttf',
			'Comfortaa-Regular' => 'Comfortaa-Regular.ttf',
			'ConcertOne-Regular' => 'ConcertOne-Regular.ttf',
			'Condiment-Regular' => 'Condiment-Regular.ttf',
			'Content-Regular' => 'Content-Regular.ttf',
			'ContrailOne-Regular' => 'ContrailOne-Regular.ttf',
			'Convergence-Regular' => 'Convergence-Regular.ttf',
			'Cookie-Regular' => 'Cookie-Regular.ttf',
			'Copse-Regular' => 'Copse-Regular.ttf',
			'Corben-Regular' => 'Corben-Regular.ttf',
			'Courgette-Regular' => 'Courgette-Regular.ttf',
			'Cousine-Regular' => 'Cousine-Regular.ttf',
			'Coustard-Regular' => 'Coustard-Regular.ttf',
			'Creepster-Regular' => 'Creepster-Regular.ttf',
			'CreepsterCaps-Regular' => 'CreepsterCaps-Regular.ttf',
			'CreteRound-Regular' => 'CreteRound-Regular.ttf',
			'CroissantOne-Regular' => 'CroissantOne-Regular.ttf',
			'Cuprum-Regular' => 'Cuprum-Regular.ttf',
			'Cutive-Regular' => 'Cutive-Regular.ttf',
			'CutiveMono-Regular' => 'CutiveMono-Regular.ttf',
			'Damion-Regular' => 'Damion-Regular.ttf',
			'DancingScript-Regular' => 'DancingScript-Regular.ttf',
			'DaysOne-Regular' => 'DaysOne-Regular.ttf',
			'Delius-Regular' => 'Delius-Regular.ttf',
			'DeliusSwashCaps-Regular' => 'DeliusSwashCaps-Regular.ttf',
			'DeliusUnicase-Regular' => 'DeliusUnicase-Regular.ttf',
			'DellaRespira-Regular' => 'DellaRespira-Regular.ttf',
			'DenkOne-Regular' => 'DenkOne-Regular.ttf',
			'Devonshire-Regular' => 'Devonshire-Regular.ttf',
			'Dhyana-Regular' => 'Dhyana-Regular.ttf',
			'Diplomata-Regular' => 'Diplomata-Regular.ttf',
			'DiplomataSC-Regular' => 'DiplomataSC-Regular.ttf',
			'Domine-Regular' => 'Domine-Regular.ttf',
			'DonegalOne-Regular' => 'DonegalOne-Regular.ttf',
			'DoppioOne-Regular' => 'DoppioOne-Regular.ttf',
			'Dorsa-Regular' => 'Dorsa-Regular.ttf',
			'Dosis-Regular' => 'Dosis-Regular.ttf',
			'DrSugiyama-Regular' => 'DrSugiyama-Regular.ttf',
			'DroidKufi-Regular' => 'DroidKufi-Regular.ttf',
			'DroidNaskh-Regular' => 'DroidNaskh-Regular.ttf',
			'DroidSansEthiopic-Regular' => 'DroidSansEthiopic-Regular.ttf',
			'DroidSansTamil-Regular' => 'DroidSansTamil-Regular.ttf',
			'DroidSansThai-Regular' => 'DroidSansThai-Regular.ttf',
			'DroidSerifThai-Regular' => 'DroidSerifThai-Regular.ttf',
			'DuruSans-Regular' => 'DuruSans-Regular.ttf',
			'Dynalight-Regular' => 'Dynalight-Regular.ttf',
			'EBGaramond-Regular' => 'EBGaramond-Regular.ttf',
			'EagleLake-Regular' => 'EagleLake-Regular.ttf',
			'Eater-Regular' => 'Eater-Regular.ttf',
			'EaterCaps-Regular' => 'EaterCaps-Regular.ttf',
			'Economica-Regular' => 'Economica-Regular.ttf',
			'Electrolize-Regular' => 'Electrolize-Regular.ttf',
			'Elsie-Regular' => 'Elsie-Regular.ttf',
			'ElsieSwashCaps-Regular' => 'ElsieSwashCaps-Regular.ttf',
			'EmblemaOne-Regular' => 'EmblemaOne-Regular.ttf',
			'EmilysCandy-Regular' => 'EmilysCandy-Regular.ttf',
			'Engagement-Regular' => 'Engagement-Regular.ttf',
			'Englebert-Regular' => 'Englebert-Regular.ttf',
			'Enriqueta-Regular' => 'Enriqueta-Regular.ttf',
			'EricaOne-Regular' => 'EricaOne-Regular.ttf',
			'Esteban-Regular' => 'Esteban-Regular.ttf',
			'EuphoriaScript-Regular' => 'EuphoriaScript-Regular.ttf',
			'Ewert-Regular' => 'Ewert-Regular.ttf',
			'Exo-Regular' => 'Exo-Regular.ttf',
			'ExpletusSans-Regular' => 'ExpletusSans-Regular.ttf',
			'FanwoodText-Regular' => 'FanwoodText-Regular.ttf',
			'Fascinate-Regular' => 'Fascinate-Regular.ttf',
			'FascinateInline-Regular' => 'FascinateInline-Regular.ttf',
			'FasterOne-Regular' => 'FasterOne-Regular.ttf',
			'Fasthand-Regular' => 'Fasthand-Regular.ttf',
			'Federant-Regular' => 'Federant-Regular.ttf',
			'Federo-Regular' => 'Federo-Regular.ttf',
			'Felipa-Regular' => 'Felipa-Regular.ttf',
			'Fenix-Regular' => 'Fenix-Regular.ttf',
			'FingerPaint-Regular' => 'FingerPaint-Regular.ttf',
			'FjallaOne-Regular' => 'FjallaOne-Regular.ttf',
			'FjordOne-Regular' => 'FjordOne-Regular.ttf',
			'Flamenco-Regular' => 'Flamenco-Regular.ttf',
			'Flavors-Regular' => 'Flavors-Regular.ttf',
			'Fondamento-Regular' => 'Fondamento-Regular.ttf',
			'Forum-Regular' => 'Forum-Regular.ttf',
			'FreckleFace-Regular' => 'FreckleFace-Regular.ttf',
			'FrederickatheGreat-Regular' => 'FrederickatheGreat-Regular.ttf',
			'FredokaOne-Regular' => 'FredokaOne-Regular.ttf',
			'Fresca-Regular' => 'Fresca-Regular.ttf',
			'Frijole-Regular' => 'Frijole-Regular.ttf',
			'Fruktur-Regular' => 'Fruktur-Regular.ttf',
			'FugazOne-Regular' => 'FugazOne-Regular.ttf',
			'GFSDidot-Regular' => 'GFSDidot-Regular.ttf',
			'Gafata-Regular' => 'Gafata-Regular.ttf',
			'Galdeano-Regular' => 'Galdeano-Regular.ttf',
			'Galindo-Regular' => 'Galindo-Regular.ttf',
			'Geo-Regular' => 'Geo-Regular.ttf',
			'Geostar-Regular' => 'Geostar-Regular.ttf',
			'GeostarFill-Regular' => 'GeostarFill-Regular.ttf',
			'GermaniaOne-Regular' => 'GermaniaOne-Regular.ttf',
			'GildaDisplay-Regular' => 'GildaDisplay-Regular.ttf',
			'GlassAntiqua-Regular' => 'GlassAntiqua-Regular.ttf',
			'Glegoo-Regular' => 'Glegoo-Regular.ttf',
			'GochiHand-Regular' => 'GochiHand-Regular.ttf',
			'Gorditas-Regular' => 'Gorditas-Regular.ttf',
			'Graduate-Regular' => 'Graduate-Regular.ttf',
			'GrandHotel-Regular' => 'GrandHotel-Regular.ttf',
			'GreatVibes-Regular' => 'GreatVibes-Regular.ttf',
			'Griffy-Regular' => 'Griffy-Regular.ttf',
			'Gruppo-Regular' => 'Gruppo-Regular.ttf',
			'Gudea-Regular' => 'Gudea-Regular.ttf',
			'Habibi-Regular' => 'Habibi-Regular.ttf',
			'HammersmithOne-Regular' => 'HammersmithOne-Regular.ttf',
			'Hanalei-Regular' => 'Hanalei-Regular.ttf',
			'HanaleiFill-Regular' => 'HanaleiFill-Regular.ttf',
			'Handlee-Regular' => 'Handlee-Regular.ttf',
			'HappyMonkey-Regular' => 'HappyMonkey-Regular.ttf',
			'HeadlandOne-Regular' => 'HeadlandOne-Regular.ttf',
			'HennyPenny-Regular' => 'HennyPenny-Regular.ttf',
			'HermeneusOne-Regular' => 'HermeneusOne-Regular.ttf',
			'HerrVonMuellerhoff-Regular' => 'HerrVonMuellerhoff-Regular.ttf',
			'Homenaje-Regular' => 'Homenaje-Regular.ttf',
			'Iceberg-Regular' => 'Iceberg-Regular.ttf',
			'Iceland-Regular' => 'Iceland-Regular.ttf',
			'Imprima-Regular' => 'Imprima-Regular.ttf',
			'Inconsolata-Regular' => 'Inconsolata-Regular.ttf',
			'Inder-Regular' => 'Inder-Regular.ttf',
			'Inika-Regular' => 'Inika-Regular.ttf',
			'IstokWeb-Regular' => 'IstokWeb-Regular.ttf',
			'Italiana-Regular' => 'Italiana-Regular.ttf',
			'Italianno-Regular' => 'Italianno-Regular.ttf',
			'JacquesFrancois-Regular' => 'JacquesFrancois-Regular.ttf',
			'JacquesFrancoisShadow-Regular' => 'JacquesFrancoisShadow-Regular.ttf',
			'JimNightshade-Regular' => 'JimNightshade-Regular.ttf',
			'JockeyOne-Regular' => 'JockeyOne-Regular.ttf',
			'JollyLodger-Regular' => 'JollyLodger-Regular.ttf',
			'JosefinSans-Regular' => 'JosefinSans-Regular.ttf',
			'JosefinSlab-Regular' => 'JosefinSlab-Regular.ttf',
			'JotiOne-Regular' => 'JotiOne-Regular.ttf',
			'Judson-Regular' => 'Judson-Regular.ttf',
			'Julee-Regular' => 'Julee-Regular.ttf',
			'JuliusSansOne-Regular' => 'JuliusSansOne-Regular.ttf',
			'Junge-Regular' => 'Junge-Regular.ttf',
			'Jura-Regular' => 'Jura-Regular.ttf',
			'Kameron-Regular' => 'Kameron-Regular.ttf',
			'Karla-Regular' => 'Karla-Regular.ttf',
			'KarlaTamilInclined-Regular' => 'KarlaTamilInclined-Regular.ttf',
			'KarlaTamilUpright-Regular' => 'KarlaTamilUpright-Regular.ttf',
			'KaushanScript-Regular' => 'KaushanScript-Regular.ttf',
			'Kavoon-Regular' => 'Kavoon-Regular.ttf',
			'KeaniaOne-Regular' => 'KeaniaOne-Regular.ttf',
			'KellySlab-Regular' => 'KellySlab-Regular.ttf',
			'Kenia-Regular' => 'Kenia-Regular.ttf',
			'KiteOne-Regular' => 'KiteOne-Regular.ttf',
			'Knewave-Regular' => 'Knewave-Regular.ttf',
			'KottaOne-Regular' => 'KottaOne-Regular.ttf',
			'Kreon-Regular' => 'Kreon-Regular.ttf',
			'KronaOne-Regular' => 'KronaOne-Regular.ttf',
			'Lancelot-Regular' => 'Lancelot-Regular.ttf',
			'Lato-Regular' => 'Lato-Regular.ttf',
			'LeckerliOne-Regular' => 'LeckerliOne-Regular.ttf',
			'Ledger-Regular' => 'Ledger-Regular.ttf',
			'Lekton-Regular' => 'Lekton-Regular.ttf',
			'Lemon-Regular' => 'Lemon-Regular.ttf',
			'LemonOne-Regular' => 'LemonOne-Regular.ttf',
			'LibreBaskerville-Regular' => 'LibreBaskerville-Regular.ttf',
			'LifeSavers-Regular' => 'LifeSavers-Regular.ttf',
			'LilitaOne-Regular' => 'LilitaOne-Regular.ttf',
			'Limelight-Regular' => 'Limelight-Regular.ttf',
			'LindenHill-Regular' => 'LindenHill-Regular.ttf',
			'LobsterTwo-Regular' => 'LobsterTwo-Regular.ttf',
			'LondrinaOutline-Regular' => 'LondrinaOutline-Regular.ttf',
			'LondrinaShadow-Regular' => 'LondrinaShadow-Regular.ttf',
			'LondrinaSketch-Regular' => 'LondrinaSketch-Regular.ttf',
			'LondrinaSolid-Regular' => 'LondrinaSolid-Regular.ttf',
			'Lora-Regular' => 'Lora-Regular.ttf',
			'LoversQuarrel-Regular' => 'LoversQuarrel-Regular.ttf',
			'Lusitana-Regular' => 'Lusitana-Regular.ttf',
			'Lustria-Regular' => 'Lustria-Regular.ttf',
			'Macondo-Regular' => 'Macondo-Regular.ttf',
			'MacondoSwashCaps-Regular' => 'MacondoSwashCaps-Regular.ttf',
			'Magra-Regular' => 'Magra-Regular.ttf',
			'Mako-Regular' => 'Mako-Regular.ttf',
			'Marcellus-Regular' => 'Marcellus-Regular.ttf',
			'MarcellusSC-Regular' => 'MarcellusSC-Regular.ttf',
			'MarckScript-Regular' => 'MarckScript-Regular.ttf',
			'Margarine-Regular' => 'Margarine-Regular.ttf',
			'MarkoOne-Regular' => 'MarkoOne-Regular.ttf',
			'Marmelad-Regular' => 'Marmelad-Regular.ttf',
			'Marvel-Regular' => 'Marvel-Regular.ttf',
			'Mate-Regular' => 'Mate-Regular.ttf',
			'MateSC-Regular' => 'MateSC-Regular.ttf',
			'MavenPro-Regular' => 'MavenPro-Regular.ttf',
			'McLaren-Regular' => 'McLaren-Regular.ttf',
			'MedulaOne-Regular' => 'MedulaOne-Regular.ttf',
			'MeieScript-Regular' => 'MeieScript-Regular.ttf',
			'MergeOne-Regular' => 'MergeOne-Regular.ttf',
			'Merienda-Regular' => 'Merienda-Regular.ttf',
			'MeriendaOne-Regular' => 'MeriendaOne-Regular.ttf',
			'Merriweather-Regular' => 'Merriweather-Regular.ttf',
			'MerriweatherSans-Regular' => 'MerriweatherSans-Regular.ttf',
			'MervaleScript-Regular' => 'MervaleScript-Regular.ttf',
			'MetalMania-Regular' => 'MetalMania-Regular.ttf',
			'Metamorphous-Regular' => 'Metamorphous-Regular.ttf',
			'Miama-Regular' => 'Miama-Regular.ttf',
			'Milonga-Regular' => 'Milonga-Regular.ttf',
			'Miltonian-Regular' => 'Miltonian-Regular.ttf',
			'MiltonianTattoo-Regular' => 'MiltonianTattoo-Regular.ttf',
			'Miniver-Regular' => 'Miniver-Regular.ttf',
			'MissFajardose-Regular' => 'MissFajardose-Regular.ttf',
			'ModernAntiqua-Regular' => 'ModernAntiqua-Regular.ttf',
			'Molengo-Regular' => 'Molengo-Regular.ttf',
			'Molle-Regular' => 'Molle-Regular.ttf',
			'Monda-Regular' => 'Monda-Regular.ttf',
			'Monoton-Regular' => 'Monoton-Regular.ttf',
			'MonsieurLaDoulaise-Regular' => 'MonsieurLaDoulaise-Regular.ttf',
			'Montaga-Regular' => 'Montaga-Regular.ttf',
			'Montez-Regular' => 'Montez-Regular.ttf',
			'Montserrat-Regular' => 'Montserrat-Regular.ttf',
			'MontserratAlternates-Regular' => 'MontserratAlternates-Regular.ttf',
			'MontserratSubrayada-Regular' => 'MontserratSubrayada-Regular.ttf',
			'MountainsofChristmas-Regular' => 'MountainsofChristmas-Regular.ttf',
			'MouseMemoirs-Regular' => 'MouseMemoirs-Regular.ttf',
			'MrBedfort-Regular' => 'MrBedfort-Regular.ttf',
			'MrDafoe-Regular' => 'MrDafoe-Regular.ttf',
			'MrDeHaviland-Regular' => 'MrDeHaviland-Regular.ttf',
			'MrsSaintDelafield-Regular' => 'MrsSaintDelafield-Regular.ttf',
			'MrsSheppards-Regular' => 'MrsSheppards-Regular.ttf',
			'Muli-Regular' => 'Muli-Regular.ttf',
			'MysteryQuest-Regular' => 'MysteryQuest-Regular.ttf',
			'NanumBrushScript-Regular' => 'NanumBrushScript-Regular.ttf',
			'NanumGothic-Regular' => 'NanumGothic-Regular.ttf',
			'NanumGothicCoding-Regular' => 'NanumGothicCoding-Regular.ttf',
			'NanumMyeongjo-Regular' => 'NanumMyeongjo-Regular.ttf',
			'NanumPenScript-Regular' => 'NanumPenScript-Regular.ttf',
			'Neuton-Regular' => 'Neuton-Regular.ttf',
			'NewRocker-Regular' => 'NewRocker-Regular.ttf',
			'NewsCycle-Regular' => 'NewsCycle-Regular.ttf',
			'Niconne-Regular' => 'Niconne-Regular.ttf',
			'NixieOne-Regular' => 'NixieOne-Regular.ttf',
			'Nobile-Regular' => 'Nobile-Regular.ttf',
			'Nokora-Regular' => 'Nokora-Regular.ttf',
			'Norican-Regular' => 'Norican-Regular.ttf',
			'Nosifer-Regular' => 'Nosifer-Regular.ttf',
			'NosiferCaps-Regular' => 'NosiferCaps-Regular.ttf',
			'NoticiaText-Regular' => 'NoticiaText-Regular.ttf',
			'Numans-Regular' => 'Numans-Regular.ttf',
			'Nunito-Regular' => 'Nunito-Regular.ttf',
			'Offside-Regular' => 'Offside-Regular.ttf',
			'OldStandard-Regular' => 'OldStandard-Regular.ttf',
			'Oldenburg-Regular' => 'Oldenburg-Regular.ttf',
			'OleoScript-Regular' => 'OleoScript-Regular.ttf',
			'OleoScriptSwashCaps-Regular' => 'OleoScriptSwashCaps-Regular.ttf',
			'OpenSans-Regular' => 'OpenSans-Regular.ttf',
			'Oranienbaum-Regular' => 'Oranienbaum-Regular.ttf',
			'Orbitron-Regular' => 'Orbitron-Regular.ttf',
			'Oregano-Regular' => 'Oregano-Regular.ttf',
			'Orienta-Regular' => 'Orienta-Regular.ttf',
			'OriginalSurfer-Regular' => 'OriginalSurfer-Regular.ttf',
			'Oswald-Regular' => 'Oswald-Regular.ttf',
			'Overlock-Regular' => 'Overlock-Regular.ttf',
			'OverlockSC-Regular' => 'OverlockSC-Regular.ttf',
			'Ovo-Regular' => 'Ovo-Regular.ttf',
			'Oxygen-Regular' => 'Oxygen-Regular.ttf',
			'OxygenMono-Regular' => 'OxygenMono-Regular.ttf',
			'PT_Sans-Caption-Web-Regular' => 'PT_Sans-Caption-Web-Regular.ttf',
			'PT_Sans-Narrow-Web-Regular' => 'PT_Sans-Narrow-Web-Regular.ttf',
			'PT_Sans-Web-Regular' => 'PT_Sans-Web-Regular.ttf',
			'PT_Serif-Caption-Web-Regular' => 'PT_Serif-Caption-Web-Regular.ttf',
			'PT_Serif-Web-Regular' => 'PT_Serif-Web-Regular.ttf',
			'Padauk-Regular' => 'Padauk-Regular.ttf',
			'Paprika-Regular' => 'Paprika-Regular.ttf',
			'Parisienne-Regular' => 'Parisienne-Regular.ttf',
			'PasseroOne-Regular' => 'PasseroOne-Regular.ttf',
			'PassionOne-Regular' => 'PassionOne-Regular.ttf',
			'PatrickHand-Regular' => 'PatrickHand-Regular.ttf',
			'PatrickHandSC-Regular' => 'PatrickHandSC-Regular.ttf',
			'PatuaOne-Regular' => 'PatuaOne-Regular.ttf',
			'Peralta-Regular' => 'Peralta-Regular.ttf',
			'PetitFormalScript-Regular' => 'PetitFormalScript-Regular.ttf',
			'Petrona-Regular' => 'Petrona-Regular.ttf',
			'Phetsarath-Regular' => 'Phetsarath-Regular.ttf',
			'Philosopher-Regular' => 'Philosopher-Regular.ttf',
			'Piedra-Regular' => 'Piedra-Regular.ttf',
			'PinyonScript-Regular' => 'PinyonScript-Regular.ttf',
			'PirataOne-Regular' => 'PirataOne-Regular.ttf',
			'Plaster-Regular' => 'Plaster-Regular.ttf',
			'Play-Regular' => 'Play-Regular.ttf',
			'Playball-Regular' => 'Playball-Regular.ttf',
			'PlayfairDisplay-Regular' => 'PlayfairDisplay-Regular.ttf',
			'PlayfairDisplaySC-Regular' => 'PlayfairDisplaySC-Regular.ttf',
			'Podkova-Regular' => 'Podkova-Regular.ttf',
			'PoetsenOne-Regular' => 'PoetsenOne-Regular.ttf',
			'PoiretOne-Regular' => 'PoiretOne-Regular.ttf',
			'Poly-Regular' => 'Poly-Regular.ttf',
			'Pompiere-Regular' => 'Pompiere-Regular.ttf',
			'PontanoSans-Regular' => 'PontanoSans-Regular.ttf',
			'PortLligatSans-Regular' => 'PortLligatSans-Regular.ttf',
			'PortLligatSlab-Regular' => 'PortLligatSlab-Regular.ttf',
			'Prata-Regular' => 'Prata-Regular.ttf',
			'PressStart2P-Regular' => 'PressStart2P-Regular.ttf',
			'PrincessSofia-Regular' => 'PrincessSofia-Regular.ttf',
			'Prociono-Regular' => 'Prociono-Regular.ttf',
			'ProstoOne-Regular' => 'ProstoOne-Regular.ttf',
			'Puritan-Regular' => 'Puritan-Regular.ttf',
			'PurplePurse-Regular' => 'PurplePurse-Regular.ttf',
			'Quando-Regular' => 'Quando-Regular.ttf',
			'Quantico-Regular' => 'Quantico-Regular.ttf',
			'Quattrocento-Regular' => 'Quattrocento-Regular.ttf',
			'QuattrocentoSans-Regular' => 'QuattrocentoSans-Regular.ttf',
			'Questrial-Regular' => 'Questrial-Regular.ttf',
			'Quicksand-Regular' => 'Quicksand-Regular.ttf',
			'Quintessential-Regular' => 'Quintessential-Regular.ttf',
			'Qwigley-Regular' => 'Qwigley-Regular.ttf',
			'RacingSansOne-Regular' => 'RacingSansOne-Regular.ttf',
			'Radley-Regular' => 'Radley-Regular.ttf',
			'Raleway-Regular' => 'Raleway-Regular.ttf',
			'RalewayDots-Regular' => 'RalewayDots-Regular.ttf',
			'Rambla-Regular' => 'Rambla-Regular.ttf',
			'RammettoOne-Regular' => 'RammettoOne-Regular.ttf',
			'Ranchers-Regular' => 'Ranchers-Regular.ttf',
			'Rancho-Regular' => 'Rancho-Regular.ttf',
			'Rationale-Regular' => 'Rationale-Regular.ttf',
			'Revalia-Regular' => 'Revalia-Regular.ttf',
			'Ribeye-Regular' => 'Ribeye-Regular.ttf',
			'RibeyeMarrow-Regular' => 'RibeyeMarrow-Regular.ttf',
			'Righteous-Regular' => 'Righteous-Regular.ttf',
			'Risque-Regular' => 'Risque-Regular.ttf',
			'Roboto-Regular' => 'Roboto-Regular.ttf',
			'RobotoCondensed-Regular' => 'RobotoCondensed-Regular.ttf',
			'Rochester-Regular' => 'Rochester-Regular.ttf',
			'Rokkitt-Regular' => 'Rokkitt-Regular.ttf',
			'Romanesco-Regular' => 'Romanesco-Regular.ttf',
			'RopaSans-Regular' => 'RopaSans-Regular.ttf',
			'Rosario-Regular' => 'Rosario-Regular.ttf',
			'Rosarivo-Regular' => 'Rosarivo-Regular.ttf',
			'RougeScript-Regular' => 'RougeScript-Regular.ttf',
			'Ruda-Regular' => 'Ruda-Regular.ttf',
			'Rufina-Regular' => 'Rufina-Regular.ttf',
			'RugeBoogie-Regular' => 'RugeBoogie-Regular.ttf',
			'Ruluko-Regular' => 'Ruluko-Regular.ttf',
			'RumRaisin-Regular' => 'RumRaisin-Regular.ttf',
			'RussoOne-Regular' => 'RussoOne-Regular.ttf',
			'Ruthie-Regular' => 'Ruthie-Regular.ttf',
			'Rye-Regular' => 'Rye-Regular.ttf',
			'Sacramento-Regular' => 'Sacramento-Regular.ttf',
			'Sail-Regular' => 'Sail-Regular.ttf',
			'Salsa-Regular' => 'Salsa-Regular.ttf',
			'Sanchez-Regular' => 'Sanchez-Regular.ttf',
			'Sancreek-Regular' => 'Sancreek-Regular.ttf',
			'Sansation-Regular' => 'Sansation-Regular.ttf',
			'Sarina-Regular' => 'Sarina-Regular.ttf',
			'Satisfy-Regular' => 'Satisfy-Regular.ttf',
			'Scada-Regular' => 'Scada-Regular.ttf',
			'SeaweedScript-Regular' => 'SeaweedScript-Regular.ttf',
			'Sedan-Regular' => 'Sedan-Regular.ttf',
			'SedanSC-Regular' => 'SedanSC-Regular.ttf',
			'Sevillana-Regular' => 'Sevillana-Regular.ttf',
			'SeymourOne-Regular' => 'SeymourOne-Regular.ttf',
			'ShadowsIntoLightTwo-Regular' => 'ShadowsIntoLightTwo-Regular.ttf',
			'Shanti-Regular' => 'Shanti-Regular.ttf',
			'Share-Regular' => 'Share-Regular.ttf',
			'ShareTech-Regular' => 'ShareTech-Regular.ttf',
			'ShareTechExp-Regular' => 'ShareTechExp-Regular.ttf',
			'ShareTechMono-Regular' => 'ShareTechMono-Regular.ttf',
			'ShareTechMonoExp-Regular' => 'ShareTechMonoExp-Regular.ttf',
			'Shojumaru-Regular' => 'Shojumaru-Regular.ttf',
			'ShortStack-Regular' => 'ShortStack-Regular.ttf',
			'Signika-Regular' => 'Signika-Regular.ttf',
			'SignikaNegative-Regular' => 'SignikaNegative-Regular.ttf',
			'Simonetta-Regular' => 'Simonetta-Regular.ttf',
			'Sintony-Regular' => 'Sintony-Regular.ttf',
			'SirinStencil-Regular' => 'SirinStencil-Regular.ttf',
			'Skranji-Regular' => 'Skranji-Regular.ttf',
			'Smokum-Regular' => 'Smokum-Regular.ttf',
			'Smythe-Regular' => 'Smythe-Regular.ttf',
			'Sniglet-Regular' => 'Sniglet-Regular.ttf',
			'SnowburstOne-Regular' => 'SnowburstOne-Regular.ttf',
			'SofadiOne-Regular' => 'SofadiOne-Regular.ttf',
			'Sofia-Regular' => 'Sofia-Regular.ttf',
			'SonsieOne-Regular' => 'SonsieOne-Regular.ttf',
			'SortsMillGoudy-Regular' => 'SortsMillGoudy-Regular.ttf',
			'Souliyo-Regular' => 'Souliyo-Regular.ttf',
			'SourceCodePro-Regular' => 'SourceCodePro-Regular.ttf',
			'SourceSansPro-Regular' => 'SourceSansPro-Regular.ttf',
			'SpicyRice-Regular' => 'SpicyRice-Regular.ttf',
			'Spinnaker-Regular' => 'Spinnaker-Regular.ttf',
			'Spirax-Regular' => 'Spirax-Regular.ttf',
			'SquadaOne-Regular' => 'SquadaOne-Regular.ttf',
			'Stalemate-Regular' => 'Stalemate-Regular.ttf',
			'StalinOne-Regular' => 'StalinOne-Regular.ttf',
			'StalinistOne-Regular' => 'StalinistOne-Regular.ttf',
			'StardosStencil-Regular' => 'StardosStencil-Regular.ttf',
			'StintUltraCondensed-Regular' => 'StintUltraCondensed-Regular.ttf',
			'StintUltraExpanded-Regular' => 'StintUltraExpanded-Regular.ttf',
			'Stoke-Regular' => 'Stoke-Regular.ttf',
			'Strait-Regular' => 'Strait-Regular.ttf',
			'Strong-Regular' => 'Strong-Regular.ttf',
			'SupermercadoOne-Regular' => 'SupermercadoOne-Regular.ttf',
			'Syncopate-Regular' => 'Syncopate-Regular.ttf',
			'Tauri-Regular' => 'Tauri-Regular.ttf',
			'Telex-Regular' => 'Telex-Regular.ttf',
			'TenorSans-Regular' => 'TenorSans-Regular.ttf',
			'TerminalDosis-Regular' => 'TerminalDosis-Regular.ttf',
			'TextMeOne-Regular' => 'TextMeOne-Regular.ttf',
			'Tharlon-Regular' => 'Tharlon-Regular.ttf',
			'Tienne-Regular' => 'Tienne-Regular.ttf',
			'Tinos-Regular' => 'Tinos-Regular.ttf',
			'TitanOne-Regular' => 'TitanOne-Regular.ttf',
			'TitilliumWeb-Regular' => 'TitilliumWeb-Regular.ttf',
			'TradeWinds-Regular' => 'TradeWinds-Regular.ttf',
			'Trocchi-Regular' => 'Trocchi-Regular.ttf',
			'Trochut-Regular' => 'Trochut-Regular.ttf',
			'Trykker-Regular' => 'Trykker-Regular.ttf',
			'Tuffy-Regular' => 'Tuffy-Regular.ttf',
			'TulpenOne-Regular' => 'TulpenOne-Regular.ttf',
			'Ubuntu-Regular' => 'Ubuntu-Regular.ttf',
			'UbuntuCondensed-Regular' => 'UbuntuCondensed-Regular.ttf',
			'UbuntuMono-Regular' => 'UbuntuMono-Regular.ttf',
			'UncialAntiqua-Regular' => 'UncialAntiqua-Regular.ttf',
			'Underdog-Regular' => 'Underdog-Regular.ttf',
			'UnicaOne-Regular' => 'UnicaOne-Regular.ttf',
			'Unkempt-Regular' => 'Unkempt-Regular.ttf',
			'Unlock-Regular' => 'Unlock-Regular.ttf',
			'Unna-Regular' => 'Unna-Regular.ttf',
			'VT323-Regular' => 'VT323-Regular.ttf',
			'VampiroOne-Regular' => 'VampiroOne-Regular.ttf',
			'Varela-Regular' => 'Varela-Regular.ttf',
			'VarelaRound-Regular' => 'VarelaRound-Regular.ttf',
			'VastShadow-Regular' => 'VastShadow-Regular.ttf',
			'Vibur-Regular' => 'Vibur-Regular.ttf',
			'Vidaloka-Regular' => 'Vidaloka-Regular.ttf',
			'Viga-Regular' => 'Viga-Regular.ttf',
			'Voces-Regular' => 'Voces-Regular.ttf',
			'Volkhov-Regular' => 'Volkhov-Regular.ttf',
			'Vollkorn-Regular' => 'Vollkorn-Regular.ttf',
			'Voltaire-Regular' => 'Voltaire-Regular.ttf',
			'Wallpoet-Regular' => 'Wallpoet-Regular.ttf',
			'Warnes-Regular' => 'Warnes-Regular.ttf',
			'Wellfleet-Regular' => 'Wellfleet-Regular.ttf',
			'WendyOne-Regular' => 'WendyOne-Regular.ttf',
			'YanoneKaffeesatz-Regular' => 'YanoneKaffeesatz-Regular.ttf',
			'Yellowtail-Regular' => 'Yellowtail-Regular.ttf',
			'YesevaOne-Regular' => 'YesevaOne-Regular.ttf',
			'Yesteryear-Regular' => 'Yesteryear-Regular.ttf'
		);
		
	}
		
}