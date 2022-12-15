const { readFileSync, existsSync, mkdirSync, rmdirSync } = require("fs");
const { copySync } = require("fs-extra");
const { zip } = require("zip-a-folder");
const mix = require("laravel-mix");
const { join } = require("path");
const os = require("os");

mix
  // scss files
  .sass("assets/scss/app.scss", "assets/css")

  // js files
  .js("assets/js/src/main.js", "assets/js/dist")

  // zip the plugin after build is done
  .after(async (webpackStats) => {
    if (mix.inProduction()) {
      const pkg = readFileSync(join(__dirname, "package.json"), "utf8");
      const json = JSON.parse(pkg);
      const dirName = join(os.tmpdir(), json.name);

      try {
        rmdirSync(dirName, { recursive: true });
        mkdirSync(dirName);

        const files = [
          // directories
          "assets",
          "lang",
          "abstracts",
          "classes",
          "helpers",
          "hooks",
          "templates",
          "vendor",

          // files
          "dependencies",
          "composer.json",
          "generate-mo-files.sh",
          "index.php",
          "package.json",
          "wp-cli.phar",
          "webpack.mix.js",
          ".gitignore",
        ];

        files.forEach((dir) => {
          if (existsSync(join(__dirname, dir))) {
            copySync(join(__dirname, dir), join(dirName, dir));
          }
        });

        await zip(dirName, join(__dirname, json.name + ".zip"));
      } catch (e) {
        console.log("Failed to pack the plugin");
      }

      rmdirSync(dirName, { recursive: true });
    }
  });
