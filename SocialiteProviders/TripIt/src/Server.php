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
        $detect = new \Mobile_Detect();
        $subdomain = $detect->isMobile() ? 'm' : 'www';

        return "https://$subdomain.tripit.com/oauth/authorize";
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
        throw new \BadMethodCallException('TripIt does not have a user endpoint');
    }

    /**
     * {@inheritDoc}
     */
    public function userDetails($data, TokenCredentials $tokenCredentials)
    {
        throw new \BadMethodCallException('TripIt does not have a user endpoint');
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
