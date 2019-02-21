<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Rtrvrtg\PayloadBuilder\PathItem;

final class PathItemTestCase extends TestCase {
  public function testParsesRootItem(): void {
    $item = PathItem::parseChunk(NULL);
    $this->assertEquals(PathItem::TYPE_ROOT, $item->type());
    $this->assertEquals(NULL, $item->value());
  }

  public function testParsesArrayItem(): void {
    $item = PathItem::parseChunk('[0]');
    $this->assertEquals(PathItem::TYPE_ARRAY, $item->type());
    $this->assertEquals(0, $item->value());
  }

  public function testParsesEmptyArrayItem(): void {
    $item = PathItem::parseChunk('[]');
    $this->assertEquals(PathItem::TYPE_ARRAY, $item->type());
    $this->assertEquals(NULL, $item->value());
  }

  public function testParsesObjectItem(): void {
    $item = PathItem::parseChunk('foo');
    $this->assertEquals(PathItem::TYPE_OBJECT, $item->type());
    $this->assertEquals('foo', $item->value());
  }

  public function testParsesEmptyObjectItem(): void {
    $item = PathItem::parseChunk('');
    $this->assertEquals(PathItem::TYPE_OBJECT, $item->type());
    $this->assertEquals('', $item->value());
  }

  public function testParsesDotObjectItem(): void {
    $item = PathItem::parseChunk('Banjaxed.');
    $this->assertEquals(PathItem::TYPE_OBJECT, $item->type());
    $this->assertEquals('Banjaxed.', $item->value());
  }

}
