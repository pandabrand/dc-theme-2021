module.exports = {
  config: {
    tailwindjs: "./tailwind.config.js",
    port: 9050,
  },
  paths: {
    root: "./",
    src: {
      base: "./static",
      css: "./static/css",
      js: "./static/js",
      npm: "./node_modules",
      img: "./static/img",
      fonts: "./static/fonts",
      twig: "./templates",
      woo: "./views"
    },
    dist: {
      base: "./dist",
      css: "./dist/css",
      js: "./dist/js",
      img: "./dist/img",
      fonts: "./dist/fonts",
    },
    build: {
      base: "./build",
      css: "./build/css",
      js: "./build/js",
      img: "./build/img",
      fonts: "./build/fonts",
    },
  },
}