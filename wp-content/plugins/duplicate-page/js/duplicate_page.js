jQuery(window).load(function (e) {
    jQuery('.dpmrs').delay(10000).slideDown('slow');
});
jQuery(document).ready(function () {
    jQuery('.close_dp_help').on('click', function (e) {
        var what_to_do = jQuery(this).data('ct');
        jQuery.ajax({
            type: "post",
            url: dp_ajax_url,
            data: {
                action: "mk_dp_close_dp_help",
                what_to_do: what_to_do
            },
            success: function (response) {
                jQuery('.dpmrs').slideUp('slow');
            }
        });
    });
});