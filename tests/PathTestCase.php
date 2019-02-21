<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Rtrvrtg\PayloadBuilder\Path;
use Rtrvrtg\PayloadBuilder\PathItem;

final class PathItemTestCase extends TestCase {
  public function testParsesRootPath(): void {
    $path = Path::parsePath('');
    $this->assertEquals($path->count(), 1);
    $this->assertEquals($path->current()->type(), PathItem::TYPE_ROOT);
    $this->assertEquals($path->current()->value(), NULL);
  }

  public function testParsesObjectPath(): void {
    $path = Path::parsePath('foo.bar');
    $this->assertEquals($path->count(), 2);
    $this->assertEquals($path->current()->type(), PathItem::TYPE_OBJECT);
    $this->assertEquals($path->current()->value(), 'foo');
    $path->next();
    $this->assertEquals($path->current()->type(), PathItem::TYPE_OBJECT);
    $this->assertEquals($path->current()->value(), 'bar');
  }

  public function testParsesStartArrayObjectPath(): void {
    $path = Path::parsePath('[0]foo');
    $this->assertEquals($path->count(), 2);
    $this->assertEquals($path->current()->type(), PathItem::TYPE_ARRAY);
    $this->assertEquals($path->current()->value(), '0');
    $path->next();
    $this->assertEquals($path->current()->type(), PathItem::TYPE_OBJECT);
    $this->assertEquals($path->current()->value(), 'foo');
  }

  public function testParsesObjectEndArrayPath(): void {
    $path = Path::parsePath('foo[0]');
    $this->assertEquals($path->count(), 2);
    $this->assertEquals($path->current()->type(), PathItem::TYPE_OBJECT);
    $this->assertEquals($path->current()->value(), 'foo');
    $path->next();
    $this->assertEquals($path->current()->type(), PathItem::TYPE_ARRAY);
    $this->assertEquals($path->current()->value(), '0');
  }

}
