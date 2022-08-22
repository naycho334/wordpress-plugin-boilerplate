'use strict';

const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const { rmSync, readdirSync, mkdirSync, cp, unlink, unlinkSync, rmdirSync, copyFileSync, lstatSync, readFileSync } = require('fs');
const { series } = require('gulp');
const { join, dirname } = require('path');
const gulp = require('gulp');
const os = require('os');
const { copySync } = require('fs-extra');
const { zip } = require('zip-a-folder');

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

exports.pack = async function () {
  const pkg = readFileSync(join(__dirname, "package.json"), "utf8");
  const json = JSON.parse(pkg);
  const dirName = join(__dirname, json.name);

  try {
    rmdirSync(dirName, { recursive: true });
    mkdirSync(dirName);

    const dirs = [
      "assets",
      "lang",
      "abstracts",
      "classes",
      "hooks",
      "templates",
      "composer.json",
      "generate-mo-files.sh",
      "gulpfile.js",
      "index.php",
      "package.json",
      "wp-cli.phar",
      ".gitignore",
    ];

    dirs.forEach((dir) => copySync(join(__dirname, dir), join(dirName, dir)));

    await zip(dirName, join(__dirname, json.name + ".zip"));

    rmdirSync(dirName, { recursive: true });
  } catch (e) {
    rmdirSync(dirName, { recursive: true });
    console.log("Failed to pack the plugin");
  }
}