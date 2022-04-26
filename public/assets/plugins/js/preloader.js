// Preloader Page
$(window).ready(function () {
    $('#preloader-page').fadeOut('normal', function () {
        $(this).remove();
        $('body').removeClass('overflow-hidden');

        AOS.init({
            disable: 'mobile',
        });
    });
});
