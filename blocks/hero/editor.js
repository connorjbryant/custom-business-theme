(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { __ } = wp.i18n;
  const { useBlockProps, RichText } = wp.blockEditor;

  registerBlockType('tlk-supply/hero', {
    edit({ attributes = {}, setAttributes }) {
      return wp.element.createElement(
        'section',
        useBlockProps({ className: 'heroblock' }),
        wp.element.createElement(RichText, {
          tagName: 'h1',
          value: attributes.heading,
          placeholder: __('Add heading…', 'tlk-supply'),
          onChange: (v) => setAttributes({ heading: v })
        })
      );
    },
    save() { return null; } // server-rendered
  });
})(window.wp);
