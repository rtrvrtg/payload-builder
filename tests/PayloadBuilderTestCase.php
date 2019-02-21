<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Rtrvrtg\PayloadBuilder\PayloadBuilder;

final class PayloadBuilderTestCase extends TestCase {
  public function testSimpleObjectPayload(): void {
    $builder = PayloadBuilder::parse("foo.bar, string, hello\nfoo.baz, string, world");
    $object = $builder->build();
    $this->assertEquals([
      'foo' => [
        'bar' => 'hello',
        'baz' => 'world',
      ],
    ], $object);
  }

  public function testSimpleArrayPayload(): void {
    $builder = PayloadBuilder::parse("[0], string, foo\n[1], string, bar");
    $object = $builder->build();
    $this->assertEquals(['foo', 'bar'], $object);
  }

  public function testRootPayload(): void {
    $builder = PayloadBuilder::parse(", string, foo");
    $object = $builder->build();
    $this->assertEquals('foo', $object);
  }

}
