//side-menu height
$(document).ready(function () {
    var divHeight = $('.main-window').height();
    console.log(divHeight);
    $('.menu').css('height', divHeight + 'px');
});

//when checked give red
$("input[type='checkbox']").change(function () {
    if ($(this).is(":checked")) {
        $(this).parent().addClass("checked");
        $("#window-override").addClass("show");
    } else {
        $(this).parent().removeClass("checked");
    }
});

//close kind-window
$("#window-override input[type='radio']").change(function () {
    if ($(this).is(":checked")) {
        $("#window-override").removeClass("show");
        $("#window-override input[type='radio']").removeAttr("checked");
    }
});
