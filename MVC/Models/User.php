<?php

namespace MVC\Models;
use System\Pattern\Singleton;
use System\Database\Connection;
/**
 * Class User
 * @package MVC\Models
 */

class User
{
  use Singleton;
/**
  @return object $this->link;
 */
  public function __construct()
  {
    $connection = Connection::getInstance();
    $this->link = $connection->getLink();
    return $this->link;
  }
  /**
   * @var string $data
   * @return string $data  secure string
   */
  public function secureString ($data)
  {
      $text_to_check = $this->link->escape_string($data);
      $text_to_check = strip_tags($text_to_check);
      $text_to_check = htmlspecialchars($text_to_check);
      $text_to_check = stripslashes($text_to_check);
      $text_to_check = addslashes($text_to_check);
      $data = $text_to_check;
      return $data;
  }
  /**
  * @var string $password
   * @return string $hash
   */
  public function hashpassword ($password)
  {
    $options = [
      'salt' => md5($password),
      //write your own code to generate a suitable salt
      'cost' => 12
      // the default cost is 10
    ];
    $hash = password_hash($password, PASSWORD_DEFAULT, $options);
    return $hash;

  }
}