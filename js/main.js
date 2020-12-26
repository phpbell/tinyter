$(function(){
    $("ul.nav li a").click(function() {
        $(this).parent().css("background-color", "#eee");
        $(this).css("background-color", "#eee");
    });
});
