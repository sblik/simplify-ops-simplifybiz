jQuery.noConflict();
jQuery(document).ready(function ($) {
    $("#input_11_18").datepicker({
        defaultDate: "+1d",
        gotoCurrent: true,
        prevText: "",
        showOn: "both",
        buttonImage:
            "https://s22280.pcdn.co/wp-content/plugins/gravityforms/images/calendar.png",
        buttonImageOnly: true,
    });
});