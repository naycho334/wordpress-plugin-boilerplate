<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('NC_Hooks_Example')) {
  class NC_Hooks_Example extends NC_Hooks
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
}
