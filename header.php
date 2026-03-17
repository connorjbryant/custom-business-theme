<!-- header.php -->
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php echo body_class(); ?>>
  <div class="site-container" id="top">
    <header>
      <?php if ( defined('SUPPORT_CUSTOM_HEADER') && SUPPORT_CUSTOM_HEADER && has_header_image() ) : ?>
        <img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
      <?php endif; ?>
    </header>