<?php

/**
 * Plugin Name: Plugin name
 * Description: Plugin boilerplate.
 * Version: 1.0.0
 * Author: Samir El Khaouti
 */

if(!defined('ABSPATH')) {
  exit;
}

define("PLUGIN_TEXT_DOMAIN", "plugin-name");
define("PLUGIN_DIR", __DIR__);
define("PLUGIN_ASSETS_URL", PLUGIN_DIR . '/assets/');

use Plugin\Classes\FlashMessage;

include __DIR__ . "/vendor/autoload.php";

$directories = [__DIR__ . "/classes",__DIR__ . "/abstracts", __DIR__ . "/hooks"];

foreach ($directories as $dir)
{
  foreach (glob("{$dir}/*.php") as $filename) include $filename;
}

// Start session
FlashMessage::getInstance();

new Plugin\Hooks\Example();
