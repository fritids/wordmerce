jQuery(document).ready(function(){
  jQuery('#wm_carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 100,
    itemMargin: 5,
    asNavFor: '#wm_slider'
  });
  
  jQuery('#wm_slider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#wm_carousel",
    smoothHeight: true
  });
});