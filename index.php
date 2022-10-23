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

$directories = [
  "helpers" => "*.helpers.php",
  "abstracts" => "*.abstract.php",
  "classes" => "*.class.php",
  "hooks" => "*.hooks.php"
];

foreach ($directories as $dir => $pattern) {
  foreach (glob(__DIR__ . "/{$dir}/{$pattern}") as $filename) include $filename;
}

// Start session
FlashMessage::getInstance();

NC_Hooks::execute(
  plugin_dir_path(__FILE__),
  [
    NC_Hooks_Example::class,
  ]
);
