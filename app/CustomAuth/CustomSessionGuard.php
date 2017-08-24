<?php
/**
 * Created by PhpStorm.
 * User: valeria.castelo
 * Date: 20/01/2017
 * Time: 12:39
 */

namespace App\CustomAuth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CustomSessionGuard extends SessionGuard {

    /**
     * Create a new authentication guard.
     *
     * @param  string  $name
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Symfony\Component\HttpFoundation\Session\SessionInterface  $session
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return self
     */
    public function __construct($name,
                                UserProvider $provider,
                                SessionInterface $session,
                                Request $request = null) {
        parent::__construct($name, $provider, $session, $request);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @param  bool   $login
     * @return Mixed
     */
    public function attempt(array $credentials = [], $remember = false, $login = true) {

        $this->fireAttemptEvent($credentials, $remember, $login);
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        //check ldap credentials
        if ($this->hasValidCredentials($user, $credentials)) {

            //unregistered user
            if (is_null($user)) {
                return AttemptResult::newUser();
            } else {
                if ($user->active == true) {
                    if ($login) {
                        $this->login($user, $remember);
                    }
                    $user->last_login = date('Y-m-d h:m:s');
                    $user->save();
                    return AttemptResult::success();
                }
            }
        }

        // If the authentication attempt fails we will fire an event so that the user
        // may be notified of any suspicious attempts to access their account from
        // an unrecognized user. A developer may listen to this event as needed.
        if ($login) {
            $this->fireFailedEvent($user, $credentials);
        }

        return AttemptResult::fail();
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials) {
        return $this->provider->validateCredentials($user, $credentials);
    }
}