<?php
namespace TDD;

use \BadMethodCallException;

class Receipt
{

  public function __construct($formatter)
  {
    $this->Formatter = $formatter;
  }
  public function subtotal(array $items = [], $coupon)
  {
      if($coupon > 1.00){
        throw new BadMethodCallException("Coupon has to be 1.00");
      }

      $sum = array_sum($items);
      if(!is_null($coupon)){
        return $sum - ($sum * $coupon);
      } else {
        return $sum;
      }
  }

  public function tax($amout)
  {
      return $this->Formatter->currencyAmount($amout * $this->tax);
  }

  public function postTaxTotal($items, $coupon)
  {
    $subtotal = $this->subtotal($items, $coupon);
    return $subtotal + $this->tax($subtotal);
  }




}


