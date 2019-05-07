<?php

namespace Rtrvrtg\PayloadBuilder;

use Rtrvrtg\PayloadBuilder\PayloadBuilderItem;
use Rtrvrtg\PayloadBuilder\Path;

class PayloadBuilder {
  protected $items;

  public function __construct(array $items) {
    $this->items = $items;
  }

  public function build() {
    $object = NULL;
    foreach ($this->items as $item) {
      $object = $item->populate($object);
    }
    return $object;
  }

  public static function parse($string, $delimiter = ', ') {
    $lines = explode("\n", str_replace("\r", "", $string));
    $items = [];
    foreach ($lines as $line) {
      $chunks = explode($delimiter, $line, 3);
      $items[] = new PayloadBuilderItem(Path::parsePath($chunks[0]), $chunks[1], $chunks[2]);
    }
    return new PayloadBuilder($items);
  }

}
