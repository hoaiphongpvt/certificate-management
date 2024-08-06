jQuery(document).ready(function($) {
    $('.user-action').change(function() {
        $('#certificationPopup').css('display', 'block');
    });

    $('#closePopup').click(function() {
        $('#certificationPopup').css('display', 'none');
    });
});
