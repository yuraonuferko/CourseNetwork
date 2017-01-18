<?php

namespace System\Database\Statement;

use System\Database\Statement;

class Insert extends Statement
{

    protected $values;

    /**
     * @param mixed $values
     * @return  array $result
     *
     *
     */
    public function setValues($values=[])
    {
      $key_first = '' ;
      $value_first = '';
      if (true === empty($values)) {
        exit(0);
      } else {
        foreach ($values as $key=>$value) {
          $key_first = $key_first.$key.',';
          $value_first = $value_first.'\''.$value.'\',';
        }
        $key = substr($key_first,0,-1);
        $value = substr($value_first,0,-1);
      }
      $result = [$key,$value];
        return $result;
    }
  /**
   * @param mixed $values
   * @return  object|null $result
   *
   *
   */
    public function execute($link,$table, $values, $criteria,$order,$limit,$offset)
    {
      $query ='INSERT INTO '.'`'.$table->table.'` '.'('.$values[0].')'.' VALUES '.'('.$values[1].');';
      $result = mysqli_query($link,$query);
      return $result;
    }

}