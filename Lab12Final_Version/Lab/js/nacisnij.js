
$(document).ready(function() {
    $(".clickable").on("click", function() {
        if (!$(this).is(":animated")) {
            $(this).css("font-size", "+=5px"); // Natychmiastowe zastosowanie zmiany rozmiaru
            $(this).animate({
                fontSize: "-=5"
            }, 10000);
        }
    });
});
