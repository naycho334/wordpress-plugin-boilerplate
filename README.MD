# WordPress plugin boilerplate
## _A great WordPress plugin boilerplate developers_

A minimalist rich WordPress plugin boilerplate repository provides a solid foundation for any type of WordPress project.

## Features

- Allows you to monitor, compile, and reduce the size of your javascript and CSS files. By using this tool, you can ensure that your code is optimized for performance and ready for production. Simply specify the files you want to process and let the tool handle the rest
- Automatically refresh your browser when you make changes to your files. By using file watching, you can see your updates in real-time, without having to manually refresh your browser. This can be especially useful for developers and designers who are working on a project and want to see their changes instantly
- Localization support
- Template support

## Tech

WordPress plugin boilerplate uses a number of open source projects to work properly:

- [Laravel-mix](https://github.com/laravel-mix/laravel-mix) - Laravel Mix is a tool for compiling and optimizing assets in a WordPress project. It can be used to concatenate and minify JavaScript and CSS files, as well as compile Sass and Less files. Laravel Mix is built on top of webpack and can be configured through a webpack.mix.js file in your WordPress project. It aims to provide a simple, easy-to-use interface for developers to use modern build tools in their WordPress projects.

## Installation

WordPress plugin boilerplate requires [Node.js](https://nodejs.org/) v10+ to run.

```sh
git clone git@github.com:naycho334/wordpress-plugin-boilerplate.git
cd wordpress-plugin-boilerplate
npm i
```

## Watch files 

Monitoring changed files

```sh
npm run watch
```
## Compile files

Automating JavaScript and SCSS Compilation and Minification, as well as Zipping Plugin Files:

```sh
npm run build
```

## License

MIT