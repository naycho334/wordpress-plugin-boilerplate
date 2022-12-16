<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('NC_Hooks')) {
  abstract class NC_Hooks
  {
    private $__template_dir = null;
    private $__plugin_dir = null;
    private $__assets_url = null;
    private $__plugin_id = null;

    abstract protected function public_hooks();
    abstract protected function common_hooks();
    abstract protected function admin_hooks();

    /**
     * Init
     */
    final public function __init()
    {
      // Initialize the plugin only once
      $initialized = wp_cache_get("__nc_initialized_hooks_list__", "nc") ?: [];

      if (!in_array($this->__plugin_id, $initialized)) {
        // load translations
        load_textdomain("plugin-text-domain",  $this->__plugin_dir . 'lang/' . get_locale() . '.mo');

        // load dependecies
        add_action('plugins_loaded', [$this, '__load_dependencies'], 99);

        $initialized[] = $this->__plugin_id;
      }

      wp_cache_set("__nc_initialized_hooks_list__", $initialized, "nc");

      $this->common_hooks();

      if (is_admin()) {
        $this->admin_hooks();
      } else {
        $this->public_hooks();
      }
    }

    /**
     * Set plugin directory
     * 
     * @param string $dir Plugin directory
     * 
     * @return NC_Hooks $this
     */
    final public function __set_plugin_dir(string $dir)
    {
      if (!is_dir($dir)) {
        throw new Exception("Plugin directory not found");
      }

      $this->__plugin_dir = $dir;
      $this->__plugin_id = basename($this->__plugin_dir);
      $this->__assets_url = plugins_url('assets', $this->__plugin_dir . '/_');
      $this->__template_dir = $this->__plugin_dir . 'templates';

      return $this;
    }

    /**
     * Load dependecies
     * 
     * @return void
     */
    final public function __load_dependencies()
    {
      foreach (glob($this->__plugin_dir . "dependencies/*.dependency.php") as $filename) include $filename;
    }

    /**
     * Execute hooks
     * 
     * @param string   $plugin_dir Plugin directory
     * @param string[] $hooks NC_Hooks classes to execute
     * 
     * @return void
     */
    final public static function execute(string $plugin_dir, array  $hooks = [])
    {
      foreach ($hooks as $hook) {
        if (class_exists($hook) && in_array(__CLASS__, class_parents($hook))) {
          $instance = new $hook();
          $instance
            ->__set_plugin_dir($plugin_dir)
            ->__init();
        }
      }
    }

    /**
     * Switch language
     * 
     * @param string $locale Language
     * 
     * @return void
     */
    final public function switch_language(string $locale)
    {
      global $l10n;

      if (isset($l10n["plugin-text-domain"])) {
        $backup = $l10n["plugin-text-domain"];
      }

      load_textdomain("plugin-text-domain", $this->__plugin_dir . 'lang/' . $locale . '.mo');

      return function () use ($backup) {
        global $l10n;

        if (isset($backup)) {
          $l10n["plugin-text-domain"] = $backup;
        }
      };
    }

    /**
     * Load template
     * 
     * @param string $template Template path
     * @param mixed[] $args Arguments to pass to a template
     * 
     * @return void
     */
    final public function load_template(string $template, array $args = [])
    {
      $template = preg_replace(['/^\//', '/.php$/'], ['', ''],  $template);
      $template = $this->get_template_dir_path("{$template}.php");
      $template = apply_filters('nc_plugin_get_template', $template);

      if (file_exists($template)) {
        extract($args);
        include $template;
      }
    }

    /**
     * Get asset url
     * 
     * @param string $path Path to asset
     * 
     * @return void
     */
    final public function get_asset_url(string $path)
    {
      $path = preg_replace(['/^\//'], [''], $path);
      $url = $this->__assets_url . '/' . $path;

      return apply_filters('nc_plugin_get_asset_url', $url);
    }

    /**
     * Get templates dir path
     * 
     * @param string $path Optional file path
     * 
     * @return string
     */
    final public function get_template_dir_path($path = '')
    {
      $path = preg_replace(['/^\//', '/\.php$/'], ['', ''], $path);
      $path = $this->__template_dir . '/' . $path . '.php';

      return apply_filters('nc_plugin_template_basename', $path);
    }
  }
}
