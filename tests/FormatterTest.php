<?php
namespace TDD\Test;
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Formatter;

class FormatterTest extends TestCase
{
    public function setUp() {
    $this->Formatter = new Formatter();
  }

  public function tearDown() {
    unset($this->Formatter);
  }
  /**
  * @dataProvider provideCurrencyAmount
  **/

  public function testCurrencyAmout($input, $expected, $message)
  {
      $this->assertSame(
            $expected,
            $this->Formatter->currencyAmout($input),
            $message
        );
  }

  public function provideCurrencyAmount()
  {
    return [
        [1, 1.00, '1 equals 1.00'],
        [1.1, 1.10, '1 equals 1.10'],
        [1.11, 1.11, '1 equals 1.11'],
        [1.111, 1.11, '1 equals 1.11'],
    ];
  }
}
