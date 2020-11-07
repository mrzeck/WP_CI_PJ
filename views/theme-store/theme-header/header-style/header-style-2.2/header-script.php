<script type="text/javascript">
    $(function(){
        var nav = $('.navigation');

        var nav_p = nav.position();

        $(window).scroll(function () {
            if ($(this).scrollTop() > nav_p.top) {
                nav.addClass('fixed');
            } else {
                nav.removeClass('fixed');
            }
        });
    });
</script>