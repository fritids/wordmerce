jQuery(document).ready(function(){

	/*
		"name",                 // string
	    "description",          // string
	    "ingredients",          // array of strings - example ["4 apples", "2oz milk"]
	    "recipeInstructions",   // string
	    "ratingValue",          // numeric string
	    "ratingCount",          // numeric string
	    "creator",              // string
	    "publisher",            // string
	    "image",                // string (image url)
	    "linkbackUrl",          // string
	    "author",               // string
	    "cookTime",             // string
	    "prepTime",             // string
	    "totalTime",            // string
	    "recipeCategory",       // string
	    "recipeCuisine",        // string
	    "recipeYield"           // string
	*/

	jQuery("#fcw_mysupermarket_buy_now").mySupermarketRecipe({
		name: function (){
	        return jQuery('#fcw_mysupermarket-name').text();
    	},
		description: function (){
	        return jQuery('#fcw_mysupermarket-description').text();
    	},
		ingredients: function (){
	        var items = [];
        	jQuery("#fcw_mysupermarket-ingredients li").each(function (){
	            items.push(jQuery(this).text());
        	});
			return items;
    	},
		recipeInstructions: function (){
	        return jQuery('#fcw_mysupermarket-recipeInstructions').text();
    	},
		ratingValue: function (){
	        return jQuery('#fcw_mysupermarket-ratingValue').text();
    	},
		ratingCount: function (){
	        return jQuery('#fcw_mysupermarket-ratingCount').text();
    	},
		creator: function (){
	        return jQuery('#fcw_mysupermarket-creator').text();
    	},
		publisher: function (){
	        return jQuery('#fcw_mysupermarket-publisher').text();
    	},
		image: function (){
	        return jQuery('#fcw_mysupermarket-image').text();
    	},
		linkbackUrl: function (){
	        return jQuery('#fcw_mysupermarket-linkbackUrl').text();
    	},
		author: function (){
	        return jQuery('#fcw_mysupermarket-author').text();
    	},
		cookTime: function (){
	        return jQuery('#fcw_mysupermarket-cookTime').text();
    	},
		prepTime: function (){
	        return jQuery('#fcw_mysupermarket-prepTime').text();
    	},
		totalTime: function (){
	        return jQuery('#fcw_mysupermarket-totalTime').text();
    	},
		recipeCategory: function (){
	        return jQuery('#fcw_mysupermarket-recipeCategory').text();
    	},
		recipeCuisine: function (){
	        return jQuery('#fcw_mysupermarket-recipeCuisine').text();
    	},
		recipeYield: function (){
	        return jQuery('#fcw_mysupermarket-recipeYield').text();
    	},
	});

});