<?php

namespace System\Database\Statement;

use System\Database\Statement;


  class Select extends Statement
  {

      /**
       * @var array|string
       */
      protected $columns = '*';

      /**
       * @param array $columns
       * @return $this
       */
      public function columns($columns=[])
      {
        $col = '';
        if (true === empty($columns)) {
          $this->columns = '*';
          } else {
            foreach ($columns as $column) {
              $col  = $col.$column.',';
            }
            $this->columns = substr($col,0,-1);
          }
          return $this;

      }
    /**
     * @param array $criteria
     * @param string $operator AND|OR
     * @return $this
     */
      public function where($criteria=[], $operator)
      {
         if (true === empty($criteria)) {
          $this->criteria = '';
        } else {
          $iterator = '';
          foreach ($criteria as $key=>$value) {
            $iterator = $iterator.$key.'='.'\''.$value.'\''.' '.$operator.' ';
          }
          $result = ' WHERE '.$iterator;
        }
        $result=$operator === 'AND' ? substr($result,0,-5): substr($result,0,-4);
        $this->criteria = $result;
        return $this;
      }
    /**
     * @param string $order name column
     * @param string  $param ASC|DESC
     * @return $this
     */
      public function order($order,$param)
      {
          if (true === empty($order)) {
          $this->order = '';
        } else {
          $this->order = ' ORDER BY '.$order.' '.$param;
          }
        return $this;
      }
       /**
     * @param object|null $link
     * @param object|null $columns
     *@param object|null $table
     * @param object|null $criteria
     * @param object|null $order
     * @param object|null $limit
     * @param object|null $offset
     * @return object $result
     */
      public function execute($link,$columns, $table, $criteria,$order,$limit,$offset)
      {
        $query = 'SELECT '.
          $columns->columns.
          ' FROM '.
          $table->table.
          $criteria->criteria.
          $order->order.
          $limit->limit.
          $offset->offset.
          ';';
        $result = mysqli_query($link,$query);
        return $result;
      }


  }