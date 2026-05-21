(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { __ } = wp.i18n;
  const { useBlockProps, RichText, URLInputButton } = wp.blockEditor;
  const el = wp.element.createElement;

  registerBlockType('custom-business-theme/holy-grail-layout', {
    edit({ attributes = {}, setAttributes }) {
      return el(
        'section',
        useBlockProps({ className: 'hgrailblock' }),

        el('aside', { className: 'hgrailaside' },
          el(RichText, {
            tagName: 'h2',
            value: attributes.sidebarHeading,
            placeholder: __('Add sidebar heading…', 'custom-business-theme'),
            onChange: (value) => setAttributes({ sidebarHeading: value })
          }),

          el(RichText, {
            tagName: 'p',
            value: attributes.sidebarContent,
            placeholder: __('Add sidebar text…', 'custom-business-theme'),
            onChange: (value) => setAttributes({ sidebarContent: value })
          })
        ),

        el('main', { className: 'hgrailmain' },
          el('nav', { className: 'hgrailnav' },
            el(RichText, {
              tagName: 'span',
              value: attributes.navText,
              placeholder: __('Add nav text…', 'custom-business-theme'),
              onChange: (value) => setAttributes({ navText: value })
            }),

            el(URLInputButton, {
              url: attributes.navUrl,
              onChange: (url) => setAttributes({ navUrl: url })
            })
          ),

          el('article', { className: 'hgrailcontent' },
            el(RichText, {
              tagName: 'h1',
              value: attributes.mainHeading,
              placeholder: __('Add main heading…', 'custom-business-theme'),
              onChange: (value) => setAttributes({ mainHeading: value })
            }),

            el(RichText, {
              tagName: 'p',
              value: attributes.mainContent,
              placeholder: __('Add main content…', 'custom-business-theme'),
              onChange: (value) => setAttributes({ mainContent: value })
            })
          )
        )
      );
    },

    save() {
      return null;
    }
  });
})(window.wp);