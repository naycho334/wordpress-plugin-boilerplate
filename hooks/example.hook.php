<?php

if (!defined('ABSPATH')) {
  exit;
}

if (class_exists(Example::class)) {
  return;
}

class Example extends Hooks
{
  public function admin_hooks()
  {
  }

  public function public_hooks()
  {
  }

  public function common_hooks()
  {
  }
}
