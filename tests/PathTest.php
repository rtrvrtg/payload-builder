<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Rtrvrtg\PayloadBuilder\Path;
use Rtrvrtg\PayloadBuilder\PathItem;

final class PathTest extends TestCase {
  public function testParsesRootPath(): void {
    $path = Path::parsePath('');
    $this->assertEquals(1, $path->count());
    $this->assertEquals(PathItem::TYPE_ROOT, $path->current()->type());
    $this->assertEquals(NULL, $path->current()->value());
  }

  public function testParsesObjectPath(): void {
    $path = Path::parsePath('foo.bar');
    $this->assertEquals(2, $path->count());
    $this->assertEquals(PathItem::TYPE_OBJECT, $path->current()->type());
    $this->assertEquals('foo', $path->current()->value());
    $path->next();
    $this->assertEquals(PathItem::TYPE_OBJECT, $path->current()->type());
    $this->assertEquals('bar', $path->current()->value());
  }

  public function testParsesStartArrayObjectPath(): void {
    $path = Path::parsePath('[0]foo');
    $this->assertEquals(2, $path->count());
    $this->assertEquals(PathItem::TYPE_ARRAY, $path->current()->type());
    $this->assertEquals(0, $path->current()->value());
    $path->next();
    $this->assertEquals(PathItem::TYPE_OBJECT, $path->current()->type());
    $this->assertEquals('foo', $path->current()->value());
  }

  public function testParsesObjectEndArrayPath(): void {
    $path = Path::parsePath('foo[0]');
    $this->assertEquals(2, $path->count());
    $this->assertEquals(PathItem::TYPE_OBJECT, $path->current()->type());
    $this->assertEquals('foo', $path->current()->value());
    $path->next();
    $this->assertEquals(PathItem::TYPE_ARRAY, $path->current()->type());
    $this->assertEquals(0, $path->current()->value());
  }

  public function testParsesMultiArrayPath(): void {
    $path = Path::parsePath('[0][1]');
    $this->assertEquals(2, $path->count());
    $this->assertEquals(PathItem::TYPE_ARRAY, $path->current()->type());
    $this->assertEquals(0, $path->current()->value());
    $path->next();
    $this->assertEquals(PathItem::TYPE_ARRAY, $path->current()->type());
    $this->assertEquals(1, $path->current()->value());
  }

  public function testParsesMultiBlankArrayPath(): void {
    $path = Path::parsePath('[][]');
    $this->assertEquals(2, $path->count());
    $this->assertEquals(PathItem::TYPE_ARRAY, $path->current()->type());
    $this->assertEquals(NULL, $path->current()->value());
    $path->next();
    $this->assertEquals(PathItem::TYPE_ARRAY, $path->current()->type());
    $this->assertEquals(NULL, $path->current()->value());
  }

  public function testPutRoot(): void {
    $path = Path::parsePath('');
    $object = $path->putValue(NULL, 'foo');
    $this->assertSame($object, 'foo');
  }

  public function testPutSingleObject(): void {
    $path = Path::parsePath('foo');
    $object = $path->putValue(NULL, 'bar');
    $this->assertSame(['foo' => 'bar'], $object);
  }

  public function testPutMultiObject(): void {
    $path = Path::parsePath('foo.bar.baz');
    $object = $path->putValue(NULL, 'bah');
    $this->assertSame(['foo' => ['bar' => ['baz' => 'bah']]], $object);
  }

  public function testPutArray(): void {
    $path = Path::parsePath('[0][1]');
    $object = $path->putValue(NULL, 'foo');
    $this->assertSame([0 => [1 => 'foo']], $object);
  }

  public function testPutArrayBlanks(): void {
    $path = Path::parsePath('[][]');
    $object = $path->putValue(NULL, 'foo');
    $this->assertSame([['foo']], $object);
  }

  public function testCombo(): void {
    $path = Path::parsePath('foo[0]');
    $object = $path->putValue(NULL, 'bar');
    $this->assertSame(['foo' => [0 => 'bar']], $object);
  }

  public function testCombo2(): void {
    $path = Path::parsePath('[0]foo');
    $object = $path->putValue(NULL, 'bar');
    $this->assertSame([['foo' => 'bar']], $object);
  }

}
