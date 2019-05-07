<?php
/**
 * @file
 * Defines a part of a JSON object path.
 */

namespace Rtrvrtg\PayloadBuilder;

require_once dirname(__FILE__) . '/Utilities.php';

/**
 * Defines a part of a JSON object path.
 */
final class PathItem {
  public const TYPE_OBJECT = 'object';
  public const TYPE_ARRAY = 'array';
  public const TYPE_ROOT = 'root';

  protected $itemType;
  protected $itemValue;

  /**
   * Constructor.
   */
  public function __construct(string $item_type, $item_value = NULL) {
    $this->itemType = $item_type;
    $this->itemValue = $item_value;
    $this->normaliseValue();
  }

  /**
   * Gets the type of the path item.
   */
  public function type() {
    return $this->itemType;
  }

  /**
   * Gets the value for the path item.
   */
  public function value() {
    return $this->itemValue;
  }

  /**
   * Normalise the path value for the current type.
   */
  protected function normaliseValue() {
    switch ($this->itemType) {
      case self::TYPE_ROOT:
        $this->itemValue = NULL;
        break;

      case self::TYPE_ARRAY:
        if (is_numeric($this->itemValue)) {
          $this->itemValue = intval($this->itemValue);
        }
        else {
          $this->itemValue = NULL;
        }
        break;

      case self::TYPE_OBJECT:
        $this->itemValue = (string) $this->itemValue;
        break;
    }
  }

  /**
   * Parse a string or null into a PathItem.
   */
  public static function parseChunk($chunk = NULL) {
    if (is_null($chunk)) {
      return new PathItem(self::TYPE_ROOT);
    }
    if (preg_match('/^\[(.*)\]$/', $chunk, $matches)) {
      return new PathItem(self::TYPE_ARRAY, $matches[1]);
    }
    return new PathItem(self::TYPE_OBJECT, $chunk);
  }

}
