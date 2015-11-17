<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function array_merge_r(array &$array1, array &$array2){
  $merged = $array1;
  foreach ( $array2 as $key => &$value )
  {
    if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
    {
      $merged [$key] = array_merge_r ( $merged [$key], $value );
    }
    else
    {
      $merged [$key] = $value;
    }
  }
  return $merged;
}