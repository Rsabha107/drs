// $(document).ready(function () {

        // Hide spinner after page load
        $(window).on('load', function() {
            $('#page-spinner').fadeOut();
        });

        // Show spinner on form submit
        $('#spinner-form').on('submit', function() {
            $('#page-spinner').fadeIn();
            if (this.checkValidity()) {
                return true; // Allow form submission if valid
            } else {
                $('#page-spinner').fadeOut();
            }
        });