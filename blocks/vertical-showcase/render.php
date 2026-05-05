<?php
if (!defined('ABSPATH')) exit;

$slides_query = new WP_Query([
    'post_type'      => 'showcase_slide',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

if (!$slides_query->have_posts()) {
    return;
}

$slides = [];

while ($slides_query->have_posts()) {
    $slides_query->the_post();

    $slides[] = [
        'title'   => get_the_title(),
        'text'    => get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags(get_the_content()), 28),
        'image'   => get_the_post_thumbnail_url(get_the_ID(), 'large'),
    ];
}

wp_reset_postdata();
?>

<section class="cb-vshowcase alignfull" data-cb-showcase>
    <div class="cb-vshowcase__left">
        <?php
        $kicker = isset($attributes['kicker']) ? trim((string) $attributes['kicker']) : 'Vertical Showcase Slider';
        ?>

        <?php if ($kicker !== '') : ?>
            <p class="cb-vshowcase__kicker"><?php echo esc_html($kicker); ?></p>
        <?php endif; ?>

        <div class="cb-vshowcase__content-track">
            <?php foreach ($slides as $index => $slide) : ?>
                <article class="cb-vshowcase__content <?php echo $index === 0 ? 'is-active' : ''; ?>">
                    <h2><?php echo esc_html($slide['title']); ?></h2>

                    <div class="cb-vshowcase__details">
                        <div class="cb-vshowcase__copy">
                            <p><?php echo esc_html($slide['text']); ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="cb-vshowcase__right">
        <div class="cb-vshowcase__image-track">
            <?php foreach ($slides as $index => $slide) : ?>
                <div class="cb-vshowcase__image <?php echo $index === 0 ? 'is-active' : ''; ?>">
                    <?php if (!empty($slide['image'])) : ?>
                        <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cb-vshowcase__thumbs">
            <?php foreach ($slides as $index => $slide) : ?>
                <button class="cb-vshowcase__thumb <?php echo $index === 0 ? 'is-active' : ''; ?>" data-slide="<?php echo esc_attr($index); ?>">
                    <?php if (!empty($slide['image'])) : ?>
                        <img src="<?php echo esc_url($slide['image']); ?>" alt="">
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="cb-vshowcase__nav">
            <button type="button" class="cb-vshowcase__prev" aria-label="Previous slide">
                <span></span>
            </button>
            <button type="button" class="cb-vshowcase__next" aria-label="Next slide">
                <span></span>
            </button>
        </div>
    </div>
</section>