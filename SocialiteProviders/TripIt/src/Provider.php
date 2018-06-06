<?php

namespace SocialiteProviders\TripIt;

use SocialiteProviders\Manager\OAuth1\AbstractProvider;
use SocialiteProviders\Manager\OAuth1\User;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'TRIPIT';

    /**
     * {@inheritDoc}
     */
    public function user()
    {
        if (! $this->hasNecessaryVerifier()) {
            throw new \InvalidArgumentException("Invalid request. Missing OAuth verifier.");
        }

        $token = $this->getToken();
        $user = $this->server->getUserDetails($token['tokenCredentials']);

        dump($user);

        return (new User())->setRaw($user->extra)->map([
            'id'       => $user->id,
            'nickname' => $user->nickname,
            'name'     => $user->name,
            'email'    => $user->email,
            'avatar'   => $user->avatar,
        ])->setToken($token->getIdentifier(), $token->getSecret());
    }

    /**
     * {@inheritDoc}
     */
    protected function hasNecessaryVerifier()
    {
        return $this->request->has('oauth_token');
    }
}
