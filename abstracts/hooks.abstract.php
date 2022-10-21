<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('Hooks')) {
  abstract class Hooks
  {
    private $__template_dir = null;
    private $__plugin_dir = null;
    private $__assets_url = null;

    abstract protected function public_hooks();
    abstract protected function common_hooks();
    abstract protected function admin_hooks();

    /**
     * Init
     */
    final public function __init()
    {
      // load translations
      load_textdomain("plugin-text-domain",  $this->__plugin_dir . 'lang/' . get_locale() . '.mo');

      // load dependecies
      add_action('plugins_loaded', [$this, '__load_dependencies'], 99);

      // enqueue scripts and styles
      add_action('admin_enqueue_scripts', [$this, '__enqueue_scripts'], 0);
      add_action('enqueue_scripts', [$this, '__enqueue_scripts'], 0);

      $this->common_hooks();

      if (is_admin()) {
        $this->admin_hooks();
      } else {
        $this->public_hooks();
      }
    }

    /**
     * Enqueue scripts and styles
     */
    final public function __enqueue_scripts()
    {
      // enqueue vuejs
      if (defined('WP_DEBUG') && WP_DEBUG) {
        wp_register_script('vue', $this->get_asset_url('js/dist/vue.3.2.41.dev.js'), ['jquery'], '2.6.10', true);
      } else {
        wp_register_script('vue', $this->get_asset_url('js/dist/vue.3.2.41.prod.js'), ['jquery'], '2.6.10', true);
      }
    }

    /**
     * Set plugin directory
     * 
     * @param string $dir Plugin directory
     * 
     * @return Hooks $this
     */
    final public function __set_plugin_dir(string $dir)
    {
      if (!is_dir($dir)) {
        throw new Exception(__("Plugin directory not found", "plugin-text-domain"));
      }

      $this->__plugin_dir = $dir;
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
      foreach (glob($this->__plugin_dir . "dependencies/*.php") as $filename) include $filename;
    }

    /**
     * Execute hooks
     * 
     * @param string   $plugin_dir Plugin directory
     * @param string[] $hooks Hooks classes to execute
     * 
     * @return void
     */
    public static function execute(string $plugin_dir, array  $hooks = [])
    {
      foreach ($hooks as $hook) {
        if (class_exists($hook)) {
          $instance = new $hook();
          $instance->__set_plugin_dir($plugin_dir);
          $instance->__init();
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
    public function switch_language(string $locale)
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
    public function load_template(string $template, array $args = [])
    {
      // remove .php extension
      $template = str_replace('.php', '', $template);
      $template = preg_replace('/^\//', '',  $template);
      $template = $this->__plugin_dir . "templates/{$template}.php";
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
    public function get_asset_url(string $path)
    {
      $path = preg_replace(['/^\//'], [''], $path);
      $url = $this->__assets_url . '/' . $path;

      return apply_filters('nc_plugin_get_asset_url', $url);
    }

    /**
     * Get templates dir path
     * 
     * @return string
     */
    public function get_template_dir_path()
    {
      return apply_filters('nc_plugin_template_basename', $this->__template_dir);
    }
  }
}
