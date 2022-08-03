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
  }
}
