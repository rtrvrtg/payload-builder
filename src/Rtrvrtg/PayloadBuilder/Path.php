<?php
/**
 * @file
 * Defines a JSON object path.
 */

namespace Rtrvrtg\PayloadBuilder;

use Rtrvrtg\PayloadBuilder\PathItem;

require_once dirname(__FILE__) . '/Utilities.php';

/**
 * Defines a JSON object path.
 */
final class Path implements \Countable, \SeekableIterator {
  protected $parts;
  protected $position = 0;

  /**
   * Constructor.
   */
  public function __construct(array $parts = []) {
    $this->parts = $parts;
    $this->normaliseParts();
  }

  /**
   * Normalise parts of the path.
   */
  protected function normaliseParts() {
    if (count($this->parts) > 1) {
      $this->parts = array_filter($this->parts, function($p) {
        return $p->type() != PathItem::TYPE_ROOT;
      });
    }
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->parts);
  }

  /**
   * {@inheritdoc}
   */
  public function seek($position) {
    $this->position = $position;
  }

  /**
   * {@inheritdoc}
   */
  public function current() {
    return $this->parts[$this->position];
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    return $this->position;
  }

  /**
   * {@inheritdoc}
   */
  public function rewind() {
    $this->position = 0;
  }

  /**
   * {@inheritdoc}
   */
  public function next() {
    $this->position += 1;
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {
    return $this->position >= 0 && $this->position < $this->count();
  }

  /**
   * Parses a string into a Path.
   */
  public static function parsePath($path = '') {
    if (empty($path)) {
      return new Path([new PathItem(PathItem::TYPE_ROOT)]);
    }

    // Breaks out brackets and stops them with periods.
    $path = preg_replace(['/\[/', '/\]/'], ['.[', '].'], $path);

    // Replace `.[` with just a `[`.
    if (preg_match('/^\.\[/', $path)) {
      $path = preg_replace('/^\.\[/', '[', $path);
    }

    // Replace all `]....` with just a `].`.
    // Handles multiple periods to handle cases like foo.bar[0].baz.
    if (preg_match('/\]\.+/', $path)) {
      $path = preg_replace('/\]\.+/', '].', $path);
    }

    // Replace `].` at the end of a selector with just a `]`.
    if (preg_match('/\]\.+$/', $path)) {
      $path = preg_replace('/\]\.+$/', ']', $path);
    }

    // Replace `]..[` with `].[`.
    $path = str_replace(']..[', '].[', $path);

    // Finally, splits on `.` before parsing each chunk.
    // We need to keep the brackets around integer values to denote that
    // they indicate a numeric array index.
    $chunks = explode('.', $path);
    return new Path(
      array_map('Rtrvrtg\PayloadBuilder\PathItem::parseChunk', $chunks)
    );
  }

  /**
   * Put a given value at the path of this object.
   */
  public function putValue($object, $value) {
    $nested = &$object;
    $is_last = FALSE;

    foreach ($this as $index => $part) {
      $is_last = $index == \array_key_last($this->parts);
      switch ($part->type()) {
        // If this is the root path item, just set the root value.
        case PathItem::TYPE_ROOT:
          $nested = $value;
          break;

        // If it's an array, work out which index we want to set the value.
        case PathItem::TYPE_ARRAY:
          $key = $part->value();
          if ($is_last) {
            if (is_null($key)) {
              array_push($nested, $value);
            }
            else {
              $nested[$key] = $value;
            }
          }
          else {
            if (!is_array($nested)) {
              $nested = [];
            }
            if (is_null($key)) {
              $nested[] = [];
              $key = \array_key_last($nested);
            }
            $nested = &$nested[$key];
          }
          break;

        // If it's an object, work out what key to set the value.
        case PathItem::TYPE_OBJECT:
          if (!$is_last && !is_array($nested)) {
            $nested = [];
          }
          if ($is_last) {
            $nested[$part->value()] = $value;
          }
          else {
            if (!array_key_exists($part->value(), $nested)) {
              $nested[$part->value()] = [];
            }
            $nested = &$nested[$part->value()];
          }
          break;
      }
    }
    return $object;
  }

}
