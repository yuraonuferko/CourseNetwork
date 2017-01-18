<?php

namespace MVC\Controllers;

use System\Config;
use System\Controller;
use System\Database\Connection;
use System\Dispatcher;
//use System\Database\Statement\Select;

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
                $login = $connection->secureString($_POST['email']);
                $password =  $connection->secureString($_POST['password']);
                $options = [
                    'salt' => md5($password),
                    //write your own code to generate a suitable salt
                    'cost' => 12
                    // the default cost is 10
                ];

                $hash = password_hash($password, PASSWORD_DEFAULT, $options);
                $query = $connection->select();
                $result = $query->execute($connection->getLink(),
                  $query->columns([]),
                  $query->from('users'),
                  $query->where(['email'=>$login,'password'=>$hash],'AND'),
                  $query->order(),
                  $query->limit(),
                  $query->offset()
                );
              if ($result->num_rows === 1) {
                    $this->forward('home/index');
                } else {
                    $this->getView()->assign('error', 'Invalid email or/and password');
                    mysqli_free_result($result);
                }

                $connection->getLink()->close();
            }

        }

        $this->getView()->view('users/login');
    }

    /**
     * Register action
     */
    public function registerAction()
    {         // test class Insert
              $name = 'Verka Serdiuchka';
              $login = 'verka@gmail.com';
              $password = 'qqq';
              $connection = Connection::getInstance();
              $query = $connection->insert();
              $result = $query->execute($connection->getLink(),
                 $query->from('users'),
                 $query-> setValues(['name'=>$name,
                   'email'=>$login,
                   'password'=>$password
                  ])
                 );
                  if (false === $result) {
                      $this->forward('home/index');
                  }
                  mysqli_free_result($result);
                  $connection->getLink()->close();
    }



    public function testAction()
    {
        $this->getView()->view('test');
    }

}