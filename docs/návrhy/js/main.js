$(function () {

    //Slider & testimonials
    $('#mySlider, #testimonials_carousel').carousel({
        pause: 'hover'
    });

    //accordion
    $(".accordion h3").eq(1).addClass("active");
    $(".accordion .accord_cont").eq(1).show();

    $(".accordion h3").click(function () {
        $(this).next(".accord_cont").slideToggle("slow")
            .siblings(".accord_cont:visible").slideUp("slow");
        $(this).toggleClass("active");
        $(this).siblings("h3").removeClass("active");
    });

});
