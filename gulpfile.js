"use strict";

const {
  readFileSync,
  readdirSync,
  mkdirSync,
  rmdirSync,
  rmSync,
} = require("fs");
const sass = require("gulp-sass")(require("sass"));
const prefixer = require("gulp-autoprefixer");
const source = require("vinyl-source-stream");
const browserify = require("browserify");
const { copySync } = require("fs-extra");
const { zip } = require("zip-a-folder");
const buffer = require("vinyl-buffer");
const minify = require("gulp-minify");
const Babelify = require("babelify");
const { series } = require("gulp");
const { join } = require("path");
const tsify = require("tsify");
const gulp = require("gulp");
const os = require("os");

const compileSCSS = (event) => {
  gulp
    .src("./assets/scss/*.scss")
    .pipe(sass().on("error", sass.logError))
    .pipe(prefixer("last 2 versions"))
    .pipe(gulp.dest("./assets/css"));

  event !== undefined && event();
};

const removeFiles = (path = "", ext = "") => {
  readdirSync(path)
    .filter((file) => file.endsWith(ext))
    .forEach((file) => rmSync(join(path, file)));
};

const removeCssFiles = (event) => {
  removeFiles(join(__dirname, "assets", "css"), "css");

  event !== undefined && event();
};

/**
 * Watch scss
 */
gulp.task("watchscss", function () {
  removeCssFiles();
  compileSCSS();
  gulp.watch(["./assets/scss/*.scss"], series(removeCssFiles, compileSCSS));
});

/**
 * Compress plugin
 */
gulp.task("pack", async function () {
  const pkg = readFileSync(join(__dirname, "package.json"), "utf8");
  const json = JSON.parse(pkg);
  const dirName = join(os.tmpdir(), json.name);

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
      "dependencies",
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
});

/**
 * Complile and minify js files
 */
gulp.task("compilejs", function () {
  return browserify([join(__dirname, "assets", "js", "src", "main.js")])
    .transform(Babelify, {
      presets: ["@babel/preset-react"],
      plugins: [
        "@babel/plugin-proposal-class-properties",
        "@babel/plugin-transform-classes",
        "@babel/plugin-transform-modules-commonjs",
      ],
    })
    .bundle()
    .pipe(source("app.js"))
    .pipe(buffer())
    .pipe(
      minify({
        ext: { min: ".min.js" },
      })
    )
    .pipe(gulp.dest(join(__dirname, "assets", "js", "dist")));
});

// watch and run compilejs task
gulp.task("watchjs", function () {
  gulp.watch([join(__dirname, "assets", "js", "src")], series("compilejs"));
});

/**
 * Complile and minify js files
 */
gulp.task("compilets", function () {
  return browserify({
    basedir: ".",
    debug: true,
    entries: ["assets/js/src/main.ts"],
    cache: {},
    packageCache: {},
  })
    .plugin(tsify)
    .bundle()
    .pipe(source("app.js"))
    .pipe(buffer())
    .pipe(
      minify({
        ext: {
          min: ".min.js",
        },
      })
    )
    .pipe(gulp.dest(join(__dirname, "assets", "js", "dist")));
});

// watch and run compilets task
gulp.task("watchts", function () {
  gulp.watch([join(__dirname, "assets", "js", "src")], series("compilets"));
});
