<?php

if (!defined('ABSPATH')) {
  exit;
}

if (class_exists(Hooks::class)) {
  return;
}

abstract class Hooks
{
  private $plugin_dir = null;
  private $assets_url = null;
  public $template_dir = null;

  abstract protected function public_hooks();
  abstract protected function common_hooks();
  abstract protected function admin_hooks();

  public function __construct()
  {
    $this->init();
    $this->common_hooks();

    if (is_admin()) {
      $this->admin_hooks();
    } else {
      $this->public_hooks();
    }

    // load translations
    load_textdomain("plugin-domain",  $this->plugin_dir . 'lang/' . get_locale() . '.mo');

    // load dependecies
    add_action('plugins_loaded', [$this, 'load_dependencies'], 99);
  }

  /**
   * Init
   */
  public function init()
  {
    $this->assets_url = plugins_url('assets', __DIR__);
    $this->plugin_dir = plugin_dir_path(__DIR__);
    $this->template_dir = $this->plugin_dir . 'templates';
  }

  /**
   * Switch language
   */
  public function switch_language(string $locale)
  {
    global $l10n;

    if (isset($l10n["plugin-domain"])) {
      $backup = $l10n["plugin-domain"];
    }

    load_textdomain("plugin-domain", $this->plugin_dir . 'lang/' . $locale . '.mo');

    return function () use ($backup) {
      global $l10n;

      if (isset($backup)) {
        $l10n["plugin-domain"] = $backup;
      }
    };
  }

  /**
   * Load dependecies
   */
  public function load_dependencies()
  {
    foreach (glob($this->plugin_dir . "dependencies/*.php") as $filename) include $filename;
  }

  /**
   * Load template
   */
  public function load_template(string $template, array $data = [])
  {
    // remove .php extension
    $template = str_replace('.php', '', $template);
    $template = $this->plugin_dir . "templates/{$template}.php";
    $template = apply_filters('plugin_template', $template);

    if (file_exists($template)) {
      extract($data);
      include $template;
    }
  }

  /**
   * Get asset url
   */
  public function get_asset_url(string $path)
  {
    $url = $this->assets_url . '/' . $path;

    return apply_filters('plugin_get_asset_url', $url);
  }
}
