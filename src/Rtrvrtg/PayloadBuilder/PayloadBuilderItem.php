<?php

namespace Rtrvrtg\PayloadBuilder;

use Rtrvrtg\PayloadBuilder\Path;
use Rtrvrtg\PayloadBuilder\PathItem;

class PayloadBuilderItem {

  public const TYPE_STRING = 'string';
  public const TYPE_NUMBER = 'number';
  public const TYPE_OBJECT = 'object';
  public const TYPE_ARRAY = 'array';
  public const TYPE_BOOLEAN = 'boolean';
  public const TYPE_NULL = 'null';

  protected $path;
  protected $dataType;
  protected $dataValue;

  public function __construct(Path $path, string $data_type, $data_value = NULL) {
    $this->path = $path;
    $this->dataType = $data_type;
    $this->dataValue = $data_value;
  }

  public function newObject() {
    $object = NULL;

    $this->path->rewind();
    switch ($this->path->current()->type()) {
      case PathItem::TYPE_ROOT:
        $object = NULL;
        break;

      case PathItem::TYPE_OBJECT:
      case PathItem::TYPE_ARRAY:
        $object = [];
        break;
    }
  }

  public function type() {
    return $this->dataType;
  }

  public function value() {
    return $this->dataValue;
  }

  public function populate($object = NULL) {
    if (is_null($object)) {
      $object = $this->newObject();
    }
    return $this->path->putValue($object, $this->dataValue);
  }

}
