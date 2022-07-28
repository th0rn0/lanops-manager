<?php
namespace App\Libraries\Facebook;

class FacebookPersistentDataHandler implements \Facebook\PersistentData\PersistentDataInterface
{
  /**
   * @var string Prefix to use for session variables.
   */
    protected $sessionPrefix = 'FBRLH_';

  /**
   * @inheritdoc
   */
    public function get($key)
    {
        return \Session::get($this->sessionPrefix . $key);
    }

  /**
   * @inheritdoc
   */
    public function set($key, $value)
    {
      if (! isset($value)) {
        throw new InvalidArgumentException('The given value is null.');
      }
        \Session::put($this->sessionPrefix . $key, $value);
    }
}
