<?php

namespace MVC\Controllers;

use System\Config;
use System\Controller;
use System\Database\Connection;
use MVC\Models\User;
use System\Auth\Session;

/**
 * Class Users
 * @package MVC\Controllers
 */
class Users extends Controller
{
    /**
     * Login action
     */
    public function loginAction()
    {
        if (true === isset($_POST['email']) && true === isset($_POST['password'])) {

            $connection = Connection::getInstance();

            if ($connection->getLink() === false) {
                $this->getView()->assign('error', 'Maintenance mode');
            } else {
              $querysecure = User::getInstance();
              $login = $querysecure->secureString($_POST['email']);
              $password = $querysecure->secureString($_POST['password']);
              $hash = $querysecure->hashpassword($password);
              $result = $connection->select()
                ->from('users')
                -> where('email', '=', $login)
                ->_and()
                -> where('password', '=', $hash)
                ->execute();

                if (false === empty($result)) {
                  $session = Session::getInstance();
                  $session->setIdentity($result[0]);
                //  TODO ?
                  $this->forward('home/index');
                } else {
                    $this->getView()->assign('error', 'Invalid email or/and password');
                    mysqli_free_result($result);
                  }
                  // $session->clearIdentity();
                  $connection->getLink()->close();
            }
        }

        $this->getView()->view('users/login');
    }

    /**
     * Register action
     */
    public function registerAction()
    {
        /*register new user */

        if (!isset($_POST['email']) && !isset($_POST['password']) &&
            !isset($_POST['name']) ) {
            $this->getView()->view('users/register');
        }
        else {
            $dbForConnect
                = Config::getInstance()->get('database');
            $link = mysqli_connect($dbForConnect['host'], $dbForConnect['username'],
                $dbForConnect['password'], $dbForConnect['database']);
            if ($link == FALSE) {
                echo "Підключення до сервера MySQL неможливе.<br> Спробуйте пізніше";
                $this->getView()->view('home/index');
            }
            $login = $_POST['email'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $options = [
                'salt' => md5($password),
                //write your own code to generate a suitable salt
                'cost' => 12
                // the default cost is 10
            ];
            $hash = password_hash($password, PASSWORD_DEFAULT, $options);
            $query_login =
                "SELECT * FROM users WHERE email='$login';";
            $result = mysqli_query($link, $query_login);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if (!$row){
                $query =
                    "INSERT INTO `users` ( `name` , `email` , `password` ) 
           VALUES ('$name', '$login', '$hash');";
                $result = mysqli_query($link, $query);
                if ($result) {
                    mysqli_free_result($result);
                    mysqli_close($link);
                    echo "Вітаємо з успішною реєстрацією". $name.'<br>'
                        .'Авторизуйтесь, будь-ласка<br>';
                    $this->getView()->view('users/login');
                }
            }
            else {
                $message = "Користувач $login вже існує.<br> Змініть email\n";
                if ($link == FALSE){$message = '';}
                echo $message;
                mysqli_free_result($result);
                mysqli_close($link);
                $this->getView()->view('users/register');
            }
        }
    }

    public function testAction()
    { //SELECT * FROM users WHERE email=\'' . $login . '\' AND password=\'' . $hash . '\';'
        $statement = Connection::getInstance()
            ->select()
            ->from('users')
            -> where('email', '=', 'lmessi@yahoo.com')
            ->_and()
            -> where('password', '=', 'lmessi@yahoo.com')
        ;


        var_dump($statement->execute());

//        $this->getView()->view('test');

    }

}