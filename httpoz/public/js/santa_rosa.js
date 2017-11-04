$(document).ready( function () {
    $("#side-bar-logout").click(function () {
        event.preventDefault();
        $("#logout-form").submit();
    });
});
