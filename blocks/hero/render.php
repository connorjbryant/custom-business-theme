<?php
if ( ! defined('ABSPATH') ) exit;

$heading = '';
if ( isset($attributes['heading']) ) {
    $heading = trim( (string) $attributes['heading'] );
}
?>
<section class="heroblock alignfull">
  <?php if ( $heading !== '' ) : ?>
    <h1 class="herotitle"><?php echo esc_html( $heading ); ?></h1>
  <?php endif; ?>
</section>
