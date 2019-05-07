<?php

if (!function_exists('array_key_last')) {
  function array_key_last(array $items = []) {
    if (empty($items)) {
      return NULL;
    }
    $keys = array_keys($items);
    return array_pop($keys);
  }
}
