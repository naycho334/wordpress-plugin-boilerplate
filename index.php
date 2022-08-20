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

use Plugin\Classes\FlashMessage;

include __DIR__ . "/vendor/autoload.php";

include __DIR__ . "/abstracts/hooks.abstract.php";

include __DIR__ . "/hooks/example.hook.php";

include __DIR__ . "/classes/flash-message.class.php";

define("PLUGIN_DIR", plugin_dir_path(__FILE__));

FlashMessage::getInstance();

new Plugin\Hooks\Example();
