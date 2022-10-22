<?php

/**
 * Plugin Name: NC Plugin name
 * Description: Plugin boilerplate.
 * Version: 1.0.0
 * Author: Samir El Khaouti
 * Author URI: http://github.com/naycho334/
 * License: GPL2
 */

if (!defined('ABSPATH')) {
  exit;
}

if (file_exists(__DIR__ . "/vendor/autoload.php")) {
  include __DIR__ . "/vendor/autoload.php";
}

$directories = [__DIR__ . "/helpers", __DIR__ . "/abstracts", __DIR__ . "/classes",  __DIR__ . "/hooks"];

foreach ($directories as $dir) {
  foreach (glob("{$dir}/*.php") as $filename) include $filename;
}

// Start session
FlashMessage::getInstance();

NC_Hooks::execute(
  plugin_dir_path(__FILE__),
  [
    NC_Hooks_Example::class,
  ]
);
