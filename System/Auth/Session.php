<?php

namespace System\Auth;

use System\Pattern\Singleton;

/**
 * Class Session
 * @package System\Auth
 */
class Session
{

    use Singleton;

    /**
     * Session constructor.
     */
    public function __construct()
    {
      session_start();

    }

    /**
     * @return bool $_SESSION['identity']
     */
    public function hasIdentity()
    {
       $_SESSION['identity'] = (empty($this->getIdentity('id'))) ? false:true;
       return $_SESSION['identity'];
    }
    /**
    * @var array $identity
     * @return  array $_SESSION
     */
    public function setIdentity($identity=[])
    {
        foreach ($identity as $key=>$value) {
        $_SESSION[$key] = $value;
        }
    }

    public function getIdentity($identity)
    {
      return $_SESSION[$identity];
    }

    public function clearIdentity()
    {
      session_unset();
      session_destroy();
    }
}
