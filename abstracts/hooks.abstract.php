<?php

namespace Plugin\Abstracts;

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
    load_textdomain(PLUGIN_TEXT_DOMAIN,  PLUGIN_DIR . '/lang/' . get_locale() . '.mo');
  }

  /**
   * Switch language
   */
  public function switch_language(string $locale){
    global $l10n;
      
    if(isset($l10n[PLUGIN_TEXT_DOMAIN])) {
      $backup = $l10n[PLUGIN_TEXT_DOMAIN];
    }

    load_textdomain(PLUGIN_TEXT_DOMAIN, PLUGIN_DIR . 'lang/' . $locale . '.mo');
    
    return function () use ($backup) {
      global $l10n;

      if (isset($backup)) {
        $l10n[PLUGIN_TEXT_DOMAIN] = $backup;
      }
    };
  }
}
