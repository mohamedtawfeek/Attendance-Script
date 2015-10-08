$('body').addClass('home_is_visible');

$('.button').on("click", function () {
    $('body').toggleClass('nav_is_visible');
});
function accept_delete() {
    return confirm("Are you sure?");
}
