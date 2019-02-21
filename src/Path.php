<?php
/**
 * @file
 * Defines a JSON object path.
 */

namespace Rtrvrtg\PayloadBuilder;

use Rtrvrtg\PayloadBuilder\PathItem;

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
    $path = preg_replace(['/\[/', '/\]/'], ['.[', '].'], $path);
    if (preg_match('/^\.\[/', $path)) {
      $path = preg_replace('/^\.\[/', '[', $path);
    }
    if (preg_match('/\]\.$/', $path)) {
      $path = preg_replace('/\]\.$/', ']', $path);
    }
    $chunks = explode('.', $path);
    return new Path(
      array_map('Rtrvrtg\PayloadBuilder\PathItem::parseChunk', $chunks)
    );
  }

}
