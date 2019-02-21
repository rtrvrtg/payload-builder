<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PathItemTestCase extends TestCase {
  public function testArrayKeyLastExists(): void {
    $this->assertEquals(TRUE, function_exists('array_key_last'));
  }

  public function testArrayKeyLastEmpty(): void {
    $this->assertEquals(NULL, array_key_last());
  }

  public function testArrayKeyLastEmptyArray(): void {
    $this->assertEquals(NULL, array_key_last([]));
  }

  public function testArrayKeyLastSingleArray(): void {
    $this->assertEquals('a', array_key_last(['a' => 'b']));
  }

  public function testArrayKeyLastMultiArray(): void {
    $this->assertEquals('c', array_key_last(['a' => 'b', 'c' => 'd']));
  }

}
