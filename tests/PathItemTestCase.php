<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Rtrvrtg\PayloadBuilder\PathItem;

final class PathItemTestCase extends TestCase {
  public function testParsesRootItem(): void {
    $item = PathItem::parseChunk(NULL);
    $this->assertEquals($item->type(), PathItem::TYPE_ROOT);
    $this->assertEquals($item->value(), NULL);
  }

  public function testParsesArrayItem(): void {
    $item = PathItem::parseChunk('[0]');
    $this->assertEquals($item->type(), PathItem::TYPE_ARRAY);
    $this->assertEquals($item->value(), 0);
  }

  public function testParsesEmptyArrayItem(): void {
    $item = PathItem::parseChunk('[]');
    $this->assertEquals($item->type(), PathItem::TYPE_ARRAY);
    $this->assertEquals($item->value(), NULL);
  }

  public function testParsesObjectItem(): void {
    $item = PathItem::parseChunk('foo');
    $this->assertEquals($item->type(), PathItem::TYPE_OBJECT);
    $this->assertEquals($item->value(), 'foo');
  }

  public function testParsesEmptyObjectItem(): void {
    $item = PathItem::parseChunk('');
    $this->assertEquals($item->type(), PathItem::TYPE_OBJECT);
    $this->assertEquals($item->value(), '');
  }

  public function testParsesDotObjectItem(): void {
    $item = PathItem::parseChunk('Banjaxed.');
    $this->assertEquals($item->type(), PathItem::TYPE_OBJECT);
    $this->assertEquals($item->value(), 'Banjaxed.');
  }

}
