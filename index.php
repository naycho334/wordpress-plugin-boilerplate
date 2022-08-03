<?php

/**
 * Plugin Name: Portfolio
 * Description: Portfolio is a simple plugin that allows you to create a portfolio of your work.
 * Version: 1.0.0
 * Author: Samir El Khaouti
 */

use Portfolio\Classes\FlashMessage;

include __DIR__ . "/vendor/autoload.php";

include __DIR__ . "/abstracts/hooks.abstract.php";

include __DIR__ . "/hooks/technologies.hook.php";
include __DIR__ . "/hooks/portfolio.hook.php";
include __DIR__ . "/hooks/projects.hook.php";
include __DIR__ . "/hooks/contact.hook.php";
include __DIR__ . "/hooks/core.hook.php";

include __DIR__ . "/classes/flash-message.class.php";

define("PORTFOLIO_PLUGIN_DIR", plugin_dir_path(__FILE__));

FlashMessage::getInstance();

new Portfolio\Hooks\Core();
new Portfolio\Hooks\Contact();
new Portfolio\Hooks\Projects();
new Portfolio\Hooks\Technologies();
new Portfolio\Hooks\Portfolio();
