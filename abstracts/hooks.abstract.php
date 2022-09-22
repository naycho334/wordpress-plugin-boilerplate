<?php

abstract class Hooks
{
  abstract protected function public_hooks();
  abstract protected function common_hooks();
  abstract protected function admin_hooks();

  public function __construct()
  {
    $this->common_hooks();

    if (is_admin()) {
      $this->admin_hooks();
    } else {
      $this->public_hooks();
    }

    // load translations
    load_textdomain("plugin-domain",  PLUGIN_DIR . '/lang/' . get_locale() . '.mo');

    // load dependecies
    add_action('plugins_loaded', [$this, 'load_dependencies'], 99);
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

    load_textdomain("plugin-domain", PLUGIN_DIR . 'lang/' . $locale . '.mo');

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
    foreach (glob(PLUGIN_DIR . "/dependencies/*.php") as $filename) include $filename;
  }

  /**
   * Load template
   */
  public function load_template(string $template, array $data = [])
  {
    $template = PLUGIN_DIR . "/templates/{$template}.php";
    $template = apply_filters('plugin_template', $template);

    if (file_exists($template)) {
      extract($data);
      include $template;
    }
  }
}
