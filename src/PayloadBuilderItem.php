<?php

namespace Rtrvrtg\PayloadBuilder;

class PayloadBuilderItem {

  const TYPE_STRING = 'string';
  const TYPE_NUMBER = 'number';
  const TYPE_OBJECT = 'object';
  const TYPE_ARRAY = 'array';
  const TYPE_BOOLEAN = 'boolean';
  const TYPE_NULL = 'null';

  protected $path;
  protected $dataType;
  protected $dataValue;

  public function __construct(string|array $path, string $data_type, mixed $data_value = NULL) {
    $this->path = $path;
    $this->dataType = $data_type;
    $this->dataValue = $data_value;
  }

  public function getValue() {}

  public function mergeValue($object) {

  }

}
