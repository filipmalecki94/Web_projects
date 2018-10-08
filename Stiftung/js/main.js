// Banner
$(window).load(function () {
    $('.flexslider').flexslider({
        animation: "fade", //change picture as fade
        slideshowSpeed: 5000, //change picture evry 5s.
        animationSpeed: 2500, //change picture for 1s. 
        controlNav: false, //no change circles 
        directionNav: false //no change arrows
    });
});

// Menu for mobile
$('button').click(function () {
    $('.menu-mobile-container').toggleClass('unclicked clicked');
    $('.burger').toggleClass('unclickedBurger clickedBurger');
    $(this).attr('data-content', 'bar');
});
