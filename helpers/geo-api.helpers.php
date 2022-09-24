<?php

/**
 * Get country by IP address
 * @return object|null
 */
function nc_get_country_by_ip_address()
{
  $ip = $_SERVER['REMOTE_ADDR'];
  $key = "nc_user_ip_{$ip}";

  $country = null;

  // check if ip is stored in memory
  $ip_memory = wp_cache_get($key);

  if ($ip_memory !== false) {
    $country = $ip_memory;
  } else {
    // check if ip is stored in cache
    $ip_cache = get_transient($key);

    if ($ip_cache) {
      $country = $ip_cache;
    } else {
      $ip_data = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));

      if ($ip_data && gettype($ip_data) === 'object') {
        $country = $ip_data;
      }

      // store ip in cache for 1 day
      set_transient($key, $country, 60 * 60 * 24);
    }

    // store ip in memory
    wp_cache_set($key, $country ? "yes" : "no");
  }

  return $country;
}
