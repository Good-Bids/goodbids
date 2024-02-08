jQuery(window).load(function () {

    // If cookie is set, scroll to the position saved in the cookie.
    if ( jQuery.cookie("scroll") !== null ) {
        jQuery(document).scrollTop( jQuery.cookie("scroll") );
        jQuery.cookie("scroll", null);
    }

    // When a button is clicked...
    jQuery('.custom-login-button').on("click", function() {

        // Set a cookie that holds the scroll position.
        jQuery.cookie("scroll", jQuery(document).scrollTop() );

    });

    jQuery('.login-button').on("click", function() {

        // Set a cookie that holds the scroll position.
        jQuery.cookie("scroll", jQuery(document).scrollTop() );

    });

});