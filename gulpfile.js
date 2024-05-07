/**
*   Gulp with Tailwind Utility framework                                
**/

const { src, dest, task, watch, series, parallel } = require('gulp');
const del = require('del'); //For Cleaning build/dist for fresh export
const options = require("./config"); //paths and other options from config.js
const browserSync = require('browser-sync').create();

const postcss = require('gulp-postcss'); //For Compiling tailwind utilities with tailwind config
const concat = require('gulp-concat'); //For Concatinating js,css files
const uglify = require('gulp-uglify');//To Minify JS files
const imagemin = require('gulp-imagemin'); //To Optimize Images
const cleanCSS = require('gulp-clean-css');//To Minify CSS files
const purgecss = require('gulp-purgecss');// Remove Unused CSS from Styles
const tailwindcss = require('tailwindcss'); 
var rev = require('gulp-rev');

const atImport = require('postcss-import');

//Note : Webp still not supported in majpr browsers including forefox
//const webp = require('gulp-webp'); //For converting images to WebP format
//const replace = require('gulp-replace'); //For Replacing img formats to webp in html
const logSymbols = require('log-symbols'); //For Symbolic Console logs :) :P 

//Load Previews on Browser on dev
function livePreview(done){
  browserSync.init({
    proxy: "http://dc-rework.lndo.site/",
    port: options.config.port || 5000
  });
  done();
} 

// Triggers Browser reload
function previewReload(done){
  console.log("\n\t" + logSymbols.info,"Reloading Browser Preview.\n");
  browserSync.reload();
  done();
}

function devStyles(){
  return src(`${options.paths.src.css}/**/*`)
    .pipe(postcss([
      tailwindcss(options.config.tailwindjs),
      require('autoprefixer'),
      require('@fullhuman/postcss-purgecss')({
        content: [
          'views/**/*.twig',
          'templates/**/*.twig'
        ],
        defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
      }),
    ]))
    .pipe(concat({ path: 'style.css'}))
    .pipe(rev())
    .pipe(dest(options.paths.dist.css))
    .pipe( rev.manifest( options.paths.dist.base + '/manifest.json', {
      base: options.paths.dist.base,
      merge: true,
    } ) )
    .pipe( dest( options.paths.dist.base ) );
}

function devScripts(){
  return src([
    `${options.paths.src.js}/libs/**/*.js`,
    `${options.paths.src.js}/**/*.js`,
    `!${options.paths.src.js}/**/external/*`
  ]).pipe(concat({ path: 'site.js'}))
  .pipe(rev())
  .pipe(dest(options.paths.dist.js))
  .pipe( rev.manifest( options.paths.dist.base + '/manifest.json', {
    base: options.paths.dist.base,
    merge: true,
  } ) )
  .pipe( dest( options.paths.dist.base ) );
}

function devImages(){
  return src(`${options.paths.src.img}/**/*`)
  .pipe(rev())
  .pipe(dest(options.paths.dist.img))
  .pipe( rev.manifest( options.paths.dist.base + '/manifest.json', {
    base: options.paths.dist.base,
    merge: true,
  } ) )
  .pipe( dest( options.paths.dist.base ) );
}

function devFonts(){
  return src(`${options.paths.src.fonts}/**/*`)
  .pipe( dest( options.paths.dist.fonts ) )
  .pipe( dest( options.paths.dist.base ) );
}

function watchFiles(){
  watch(`${options.paths.src.twig}/**/*.twig`,series(devStyles, previewReload));
  watch([options.config.tailwindjs, `${options.paths.src.css}/**/*`],series(devStyles, previewReload));
  watch(`${options.paths.src.js}/**/*.js`,series(devScripts, previewReload));
  watch(`${options.paths.src.img}/**/*`,series(devImages, previewReload));
  console.log("\n\t" + logSymbols.info,"Watching for Changes..\n");
}

function devClean(){
  console.log("\n\t" + logSymbols.info,"Cleaning dist folder for fresh start.\n");
  return del([options.paths.dist.base]);
}

function prodStyles(){
  return src(`${options.paths.src.css}/**/*`)
  .pipe(postcss([
    atImport(),
    tailwindcss(options.config.tailwindjs),
    require('autoprefixer'),
    require('@fullhuman/postcss-purgecss')({
      content: [
        '**/*.{twig,js}',
        'views/**/*.twig',
        'templates/**/*.twig'
    ],
      defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
    }),
  ]))
  .pipe(cleanCSS({compatibility: 'ie8'}))
  .pipe(concat({ path: 'style.css'}))
  .pipe(rev())
  .pipe(dest(options.paths.build.css))
  .pipe( rev.manifest( options.paths.build.base + '/manifest.json', {
    base: options.paths.build.base,
    merge: true,
  } ) )
  .pipe( dest( options.paths.build.base ) );
}

function prodScripts(){
  return src([
    `${options.paths.src.js}/libs/**/*.js`,
    `${options.paths.src.js}/**/*.js`,
  ])
  .pipe(concat({ path: 'site.js'}))
  .pipe(uglify())
  .pipe(rev())
  .pipe(dest(options.paths.build.js))
  .pipe( rev.manifest( options.paths.build.base + '/manifest.json', {
    base: options.paths.build.base,
    merge: true,
  } ) )
  .pipe( dest( options.paths.build.base ) );
}

function prodImages(){
  return src(options.paths.src.img + '/**/*').pipe(imagemin())
  .pipe(rev())
  .pipe(dest(options.paths.build.img))
  .pipe( rev.manifest( options.paths.build.base + '/manifest.json', {
    base: options.paths.build.base,
    merge: true,
  } ) )
  .pipe( dest( options.paths.build.base ) );
}

function prodFonts(){
  return src(options.paths.src.fonts + '/**/*')
  .pipe( dest( options.paths.build.fonts) )
  .pipe( dest( options.paths.build.base ) );
}

function prodClean(){
  console.log("\n\t" + logSymbols.info,"Cleaning build folder for fresh start.\n");
  return del([options.paths.build.base]);
}

function buildFinish(done){
  console.log("\n\t" + logSymbols.info,`Production build is complete. Files are located at ${options.paths.build.base}\n`);
  done();
}

exports.default = series(
  devClean, // Clean Dist Folder
  parallel(devStyles, devScripts, devImages, devFonts), //Run All tasks in parallel
  livePreview, // Live Preview Build
  watchFiles // Watch for Live Changes
);

exports.prod = series(
  prodClean, // Clean Build Folder
  parallel(prodStyles, prodScripts, prodImages, prodFonts), //Run All tasks in parallel
  buildFinish
);