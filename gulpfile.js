'use strict';

const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const { rmSync, readdirSync } = require('fs');
const { series } = require('gulp');
const { join } = require('path');
const gulp = require('gulp');

const compileSCSS = (event) => {
  gulp.src('./scss/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./assets/css'));

  event !== undefined && event();
}

const autoPrefixCss = (event) => {
  gulp.src('./assets/css/**/*.css')
    .pipe(autoprefixer({
      cascade: false,
      flexbox: true,
      supports: true,
    }))
    .pipe(gulp.dest('./assets/css'));

  event !== undefined && event();
}

const removeFiles = (path = '', ext = '') => {
  readdirSync(path)
    .filter(file => file.endsWith(ext))
    .forEach(file => rmSync(join(path, file)));
}

const removeCssFiles = (event) => {
  removeFiles(join(__dirname, 'assets', 'css'), 'css');

  event !== undefined && event();
}

exports.watchscss = function () {
  removeCssFiles();
  compileSCSS();
  gulp.watch(['./scss/*.scss'], series(
    removeCssFiles,
    compileSCSS,
    autoPrefixCss
  ));
};