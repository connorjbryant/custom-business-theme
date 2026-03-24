# TLK Supply Theme

A custom WordPress theme built for TLK Supply.  
This theme uses **Sass** and modern **JavaScript** compiled into minified assets in a `/build` folder for better performance.

## Folder Structure

```text
custom-business-theme/
├── build/
│   ├── css/
│   │   ├── style.min.css       # Compiled & minified frontend CSS
│   │   └── editor.min.css      # Compiled & minified editor CSS (optional)
│   └── js/
│       └── main.min.js         # Compiled & minified JS
├── scss/
│   ├── style.scss              # Main Sass entrypoint (frontend)
│   └── editor.scss             # Optional Sass entrypoint (block editor)
├── src/
│   └── js/
│       └── main.js             # Authoring JS
├── blocks/
│   └── hero/
│       ├── block.json
│       ├── editor.js
│       └── render.php
├── functions.php
└── style.css                   # Theme header only

All source files live in scss/ and src/js/

All compiled files live in build/ (what WordPress enqueues)

Development Setup
Make sure you have Node.js installed (v16+ recommended)

Helpful Commands:
npm install 
Will install dependencies (run in theme directory)

npm run build 
Recompiles Sass and JS

npm run watch
Watch for changes during development
This watches scss/*.scss and src/js/*.js for changes and automatically re-compiles into build/
