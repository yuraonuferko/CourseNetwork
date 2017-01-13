<?php

namespace MVC\Controllers;

use System\Config;
use System\Dispatcher;

/**
 * Class Users
 * @package MVC\Controllers
 */
class Users
{

    /**
     * Login action
     */
    public function loginAction()
    {
        if (!isset($_POST['email']) && !isset($_POST['password'])) {
          Dispatcher::getInstance()->display('users/login');
        }
        else {
          //temporary connect  to database
          $dbForConnect = include 'config/database.php';
          $link = mysqli_connect($dbForConnect['host'], $dbForConnect['username'],
            $dbForConnect['password'],$dbForConnect['database'])
            or die("Could not connect: " . mysqli_error($link));

          if (mysqli_connect_errno()) {
            printf("Підключення до сервера MySQL неможливе. Код помилки: %s\n", mysqli_connect_error());
            exit;
          }
          $login = self::SQLSecurity($link,$_POST['email']);
          $password =  self::SQLSecurity($link,$_POST['password']);
          $query =
              "SELECT * FROM users WHERE email='$login' AND password='$password';";
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          if ($row) {
            mysqli_free_result($result);
            mysqli_close($link);
            session_start();
            $_SESSION['id'] = $row['id'];
            $row['session'] = $_SESSION['id'];
            echo "Ви успішно зараєстровані!!!".'<br>';
            return var_dump($row);
          }
          else {
            echo "Невірний логін/пароль";
            mysqli_free_result($result);
            mysqli_close($link);
          }
        }
      }
  /**
   * Security for SQL injection
   * @var $link $data
   * @ return $data
   */
    public static function SQLSecurity($link,$data)
  {
    $text_to_check = mysqli_real_escape_string ($link,$data);

    $text_to_check = strip_tags($text_to_check);

    $text_to_check = htmlspecialchars($text_to_check);

    $text_to_check = stripslashes($text_to_check);

    $text_to_check = addslashes($text_to_check);

    $data = $text_to_check;
    return $data;
  }
    /**
     * Register action
     */
    public function registerAction()
    {
        Dispatcher::getInstance()->display('users/register');
    }

    public function testAction()
    {
        $dbHost
            = Config::getInstance()->get('database', 'host', 'localhost');
        var_dump($dbHost);
    }

}