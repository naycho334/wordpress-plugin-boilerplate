<?php

if (!function_exists('nc_get_exchange_rate')) {

  /**
   * Get exchange rate for a currency
   * 
   * @param string $base_currency Base currency
   * @param string $target_currency Target currency
   * @return float
   */
  function nc_get_exchange_rate($base_currency, $target_currency)
  {
    // check if cached in the memory
    $exchange_rate = wp_cache_get('nc_exchange_rate');

    if ($exchange_rate !== false) {
      return $exchange_rate;
    }

    // check if cached in the database
    $exchange_rate = get_transient('nc_exchange_rate');

    if ($exchange_rate) {
      return $exchange_rate;
    }

    $exchange_rate = 0;
    $base_currency = strtoupper($base_currency);
    $target_currency = strtoupper($target_currency);

    $url = "https://api.exchangerate.host/latest?base={$base_currency}&symbols={$target_currency}";
    $response = wp_remote_get($url);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['rates'][$target_currency])) {
      $exchange_rate = $data['rates'][$target_currency];
    }

    // cache in the memory
    wp_cache_set('nc_exchange_rate', $exchange_rate);

    // cache in the database
    set_transient('nc_exchange_rate', $exchange_rate, HOUR_IN_SECONDS);

    return $exchange_rate;
  }
}

if (!function_exists('nc_convert_price')) {
  /**
   * Convert price to a target currency
   * 
   * @param float $price Price
   * @param string $base_currency Base currency
   * @param string $target_currency Target currency
   * 
   * @return float
   */
  function nc_convert_price($price, $base_currency, $target_currency)
  {
    $exchange_rate = nc_get_exchange_rate($base_currency, $target_currency);

    return $price * $exchange_rate;
  }
}
