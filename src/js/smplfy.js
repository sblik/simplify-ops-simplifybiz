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

    $(".datepicker").attr("autocomplete", "off");

    $('.gv-read-only input, .gv-read-only textarea').attr('readonly', true);
    
    // Prevent select changes but keep value submittable
    $('.gv-read-only select').on('mousedown', function(e) {
        e.preventDefault();
    });

});
