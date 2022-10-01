<?php

/**
 * Plugin Name: Plugin name
 * Description: Plugin boilerplate.
 * Version: 1.0.0
 * Author: Samir El Khaouti
 */

if (!defined('ABSPATH')) {
  exit;
}

if (file_exists(__DIR__ . "/vendor/autoload.php")) {
  include __DIR__ . "/vendor/autoload.php";
}

$directories = [__DIR__ . "/classes", __DIR__ . "/abstracts", __DIR__ . "/hooks", __DIR__ . "/helpers"];

foreach ($directories as $dir) {
  foreach (glob("{$dir}/*.php") as $filename) include $filename;
}

// Start session
FlashMessage::getInstance();

new Example();
