<?php
if ( ! defined('ABSPATH') ) exit;

$sidebar_heading = ! empty($attributes['sidebarHeading']) ? trim((string) $attributes['sidebarHeading']) : '';
$sidebar_content = ! empty($attributes['sidebarContent']) ? trim((string) $attributes['sidebarContent']) : '';
$main_heading    = ! empty($attributes['mainHeading']) ? trim((string) $attributes['mainHeading']) : '';
$main_content    = ! empty($attributes['mainContent']) ? trim((string) $attributes['mainContent']) : '';
$nav_text = ! empty($attributes['navText']) ? trim((string) $attributes['navText']) : '';
$nav_url  = ! empty($attributes['navUrl']) ? trim((string) $attributes['navUrl']) : '';
?>

<section class="hgrailblock alignfull">

  <aside class="hgrailaside">
    <?php if ( $sidebar_heading !== '' ) : ?>
      <h2><?php echo esc_html( $sidebar_heading ); ?></h2>
    <?php endif; ?>

    <?php if ( $sidebar_content !== '' ) : ?>
      <p><?php echo wp_kses_post( $sidebar_content ); ?></p>
    <?php endif; ?>
  </aside>

  <main class="hgrailmain">
    <?php if ( $nav_text !== '' && $nav_url !== '' ) : ?>
      <nav class="hgrailnav">
        <a href="<?php echo esc_url( $nav_url ); ?>">
          <?php echo esc_html( $nav_text ); ?>
        </a>
      </nav>
    <?php endif; ?>

    <article class="hgrailcontent">
      <?php if ( $main_heading !== '' ) : ?>
        <h1><?php echo esc_html( $main_heading ); ?></h1>
      <?php endif; ?>

      <?php if ( $main_content !== '' ) : ?>
        <p><?php echo wp_kses_post( $main_content ); ?></p>
      <?php endif; ?>
    </article>
  </main>

</section>