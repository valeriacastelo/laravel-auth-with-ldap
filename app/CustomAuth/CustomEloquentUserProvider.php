<?php
/**
 * Created by PhpStorm.
 * User: valeria.castelo
 * Date: 17/01/2017
 * Time: 13:01
 */

namespace App\CustomAuth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Str;
use App\Ldap\Ldap;

class CustomEloquentUserProvider extends EloquentUserProvider
{
    public function __construct(HasherContract $hasher, $model) {
        parent::__construct($hasher, $model);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user = null, array $credentials) {
        $username = "";
        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $username = $value;
            }
        }
        $password = $credentials['password'];

        $ldap = Ldap::authenticate($username, $password);

        return $ldap;
    }

}