<?php

namespace SocialiteProviders\TripIt;

use SocialiteProviders\Manager\OAuth1\User;
use League\OAuth1\Client\Credentials\TokenCredentials;
use League\OAuth1\Client\Credentials\CredentialsInterface;
use SocialiteProviders\Manager\OAuth1\Server as BaseServer;

class Server extends BaseServer
{
    /**
     * {@inheritDoc}
     */
    protected $responseType = 'xml';

    /**
     * {@inheritDoc}
     */
    public function urlTemporaryCredentials()
    {
        return 'https://api.tripit.com/oauth/request_token';
    }

    /**
     * {@inheritDoc}
     */
    public function urlAuthorization()
    {
        return 'https://www.tripit.com/oauth/authorize';
    }

    /**
     * {@inheritDoc}
     */
    public function urlTokenCredentials()
    {
        return 'https://api.tripit.com/oauth/access_token';
    }

    /**
     * {@inheritDoc}
     */
    public function urlUserDetails()
    {
        return 'https://api.tripit.com/v1/list/trip';
    }

    /**
     * {@inheritDoc}
     */
    public function userDetails($data, TokenCredentials $tokenCredentials)
    {
        $user           = new User();
        $user->id       = $data['id'];
        $user->nickname = $data['nickname'];
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->avatar   = $data['avatar'];

        $used = ['id', 'nickname', 'name', 'email', 'avatar'];

        dump($data);

        foreach ($data as $key => $value) {
            if (!in_array($key, $used)) {
                $used[] = $key;
            }
        }

        $user->extra = array_diff_key($data, array_flip($used));

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function userUid($data, TokenCredentials $tokenCredentials)
    {
        return $data['id'];
    }

    /**
     * {@inheritDoc}
     */
    public function userEmail($data, TokenCredentials $tokenCredentials)
    {
        return $data['email'];
    }

    /**
     * {@inheritDoc}
     */
    public function userScreenName($data, TokenCredentials $tokenCredentials)
    {
        return $data['screen_name'];
    }

    /**
     * {@inheritDoc}
     */
    protected function createTemporaryCredentials($body) {
        return parent::createTemporaryCredentials($body . '&oauth_callback_confirmed=true');
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($temporaryIdentifier) {
        return parent::getAuthorizationUrl($temporaryIdentifier) . '&oauth_callback=' . $this->clientCredentials->getCallbackUri();
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders(CredentialsInterface $credentials, $method, $url, array $bodyParameters = array())
    {
        $header = $this->protocolHeader(strtoupper($method), $url, $credentials);
        $authorizationHeader = array('Authorization' => $header);
        $headers = $this->buildHttpClientHeaders($authorizationHeader);

        return $headers;
    }
}
