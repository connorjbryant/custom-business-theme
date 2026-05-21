jQuery(document).ready(function ($) {
  console.log('setup working');

  /* Animation Reveal */
  const $reveals = $('.reveal');

  if (!$reveals.length) {
    return;
  }

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        $(entry.target).addClass('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15
  });

  $reveals.each(function () {
    observer.observe(this);
  });
});