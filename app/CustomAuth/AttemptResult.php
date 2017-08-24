<?php
/**
 * Created by PhpStorm.
 * User: valeria.castelo
 * Date: 20/01/2017
 * Time: 13:59
 */

namespace App\CustomAuth;


class AttemptResult {
    private static $SUCCESS = 0;
    private static $NEW_USER = 1;
    private static $FAIL = 2;

    private $result;

    private function __construct($result) {
        $this->result = $result;
    }

    public static function success() {
        return new AttemptResult(self::$SUCCESS);
    }

    public static function newUser() {
        return new AttemptResult(self::$NEW_USER);
    }

    public static function fail() {
        return new AttemptResult(self::$FAIL);
    }

    public function isSuccess() {
        return $this->result == self::$SUCCESS;
    }

    public function isNewUser() {
        return $this->result == self::$NEW_USER;
    }

    public function isFail() {
        return $this->result == self::$FAIL;
    }
}