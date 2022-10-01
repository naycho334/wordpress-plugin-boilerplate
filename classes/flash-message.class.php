<?php

if (!defined('ABSPATH')) {
  exit;
}

if (class_exists(FlashMessage::class)) {
  return;
}

class FlashMessage
{
  public static $instance = null;

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new FlashMessage();
    }

    return self::$instance;
  }

  public function set($data, $type = 'success')
  {
    $_SESSION['flash_message'] = [
      'data' => $data,
      'type' => $type,
    ];
  }

  public function get()
  {
    if (session_id() && isset($_SESSION['flash_message'])) {
      $message = $_SESSION['flash_message'];

      session_destroy();

      return $message;
    }

    return null;
  }
}
