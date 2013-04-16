$(document).ready(function(){

	var i = 0;

	$.each(elements, function(index, value){
		
		var element = index;
		var images = value.images;
		var bxslider_options = value.options;

		if(images.length > 0){

			$(element).html('');
			$(element).append("<ul class='bxslider' id='bxslider_"+i+"'></ul>");
			$(images).each(function(ii){
				$("#bxslider_"+i).append($('<li>', {
		        	html: '<img src="'+images[ii]+'" />'
		        }));	
			})
			
			$("#bxslider_"+i).bxSlider(bxslider_options);
			
		}else{
			//console.log(bxslider_options);
			$(element).bxSlider(bxslider_options);
			
		}
		
		
		i++;
		
	});
	
});