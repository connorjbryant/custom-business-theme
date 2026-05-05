(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { __ } = wp.i18n;
  const { useBlockProps, RichText } = wp.blockEditor;

  registerBlockType('custom-business-theme/vertical-showcase', {
    edit({ attributes = {}, setAttributes }) {
      return wp.element.createElement(
        'section',
        useBlockProps({ className: 'cb-vshowcase-editor' }),
        wp.element.createElement(RichText, {
          tagName: 'p',
          className: 'cb-vshowcase__kicker',
          value: attributes.kicker,
          placeholder: __('Add kicker…', 'custom-business-theme'),
          onChange: (value) => setAttributes({ kicker: value })
        }),
        wp.element.createElement('strong', {}, __('Vertical Showcase Slider', 'custom-business-theme')),
        wp.element.createElement('p', {}, __('Frontend renders dynamic showcase slides.', 'custom-business-theme'))
      );
    },
    save() {
      return null;
    }
  });
})(window.wp);