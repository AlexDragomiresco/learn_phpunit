<?php

namespace TDD\Test;
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';


use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
  public function setUp() {
    $this->Formatter = $this->getMockBuilder('TDD\Formatter')
          ->setMethods(['currencyAmount'])
          ->getMock();
    $this->Formatter->expects($this->any())
          ->method('currencyAmount')
          ->with($this->anything())
          ->will($this->returnArgument(0));
    $this->Receipt = new Receipt($this->Formatter);
  }

  public function tearDown() {
    unset($this->Receipt);
  }

 /**
 * @dataProvider provideSubtotal
 **/

  public function testSubtotal($items, $expected)
  {
    $coupon = null;
    $output = $this->Receipt->subtotal($items, $coupon);

    $this->assertEquals(
      $expected,
      $output,
      "Sum has to be {$expected}"
      );
  }

  public function provideSubtotal()
  {
      return [
          [[1,3,5,7], 16],
          [[0,3,5,7], 15],
          'sum is 10' => [[1,-3,5,7], 10],
      ];
  }

   public function testSubtotalAndCoupon()
  {
    $input = [1,3,5,7];
    $coupon = 0.20;
    $output = $this->Receipt->subtotal($input, $coupon);

    $this->assertEquals(
      12.8,
      $output,
      'Total with 20%off has to be 14'
      );
  }

  public function testSubtotalException()
  {
    $input = [1,3,5,7];
    $coupon = 1.20;
    $this->expectException('BadMethodCallException');
    $this->Receipt->subtotal($input, $coupon);
  }

  public function testPostTaxTotal()
  {
    $items = [1,3,5,7];
    $tax = 0.20;
    $coupon = null;

    $Receipt = $this->getMockBuilder('TDD\Receipt')
              ->setMethods(['tax', 'subtotal'])
              ->setConstructorArgs([$this->Formatter])
              ->getMock();
    $Receipt->expects($this->once())
            ->method('subtotal')
            ->with($items, $coupon)
            ->will($this->returnValue(10.00));
    $Receipt->expects($this->once())
            ->method('tax')
            ->with(10.00)
            ->will($this->returnValue(1.00));

    $result = $Receipt->postTaxTotal([1,3,5,7], null);

    $this->assertEquals(11.00, $result);
  }

  public function testTax()
  {
    $inputAmout = 10.00;
    $this->Receipt->tax = 0.10;
    $output = $this->Receipt->tax($inputAmout);
    $this->assertEquals(1.00, $output, 'Tax has to be 1.00');
  }
}
