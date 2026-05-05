jQuery(function ($) {
    $('[data-cb-showcase]').each(function () {
        const $slider = $(this);
        const $content = $slider.find('.cb-vshowcase__content');
        const $images = $slider.find('.cb-vshowcase__image');
        const $thumbs = $slider.find('.cb-vshowcase__thumb');
        const total = $content.length;

        let current = 0;

        function goToSlide(index) {
            current = (index + total) % total;

            $content.removeClass('is-active').eq(current).addClass('is-active');
            $images.removeClass('is-active').eq(current).addClass('is-active');
            $thumbs.removeClass('is-active').eq(current).addClass('is-active');
        }

        $slider.find('.cb-vshowcase__next').on('click', function () {
            goToSlide(current + 1);
        });

        $slider.find('.cb-vshowcase__prev').on('click', function () {
            goToSlide(current - 1);
        });

        $thumbs.on('click', function () {
            goToSlide(parseInt($(this).data('slide'), 10));
        });
    });
});