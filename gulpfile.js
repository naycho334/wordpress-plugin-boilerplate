'use strict';

const sass = require('gulp-sass')(require('sass'));
const { parallel } = require('gulp');
const gulp = require('gulp');

const portfolioStyle = () => gulp.src('./scss/portfolio.scss')
  .pipe(sass().on('error', sass.logError))
  .pipe(gulp.dest('./css'));

exports.portfolioStyle = parallel(portfolioStyle);
exports.watch = function () {
  portfolioStyle();

  gulp.watch('./scss/portfolio.scss', function (event) {
    console.log('Generating styles...');
    portfolioStyle();
    event();
  });
};