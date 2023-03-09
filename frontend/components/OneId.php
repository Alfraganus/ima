<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

/**
 *
 * @property-read string $loginLink
 * @property-read string getOneAccessTokenIdentify
 * @property-read mixed $userData
 * @property-read string $oneAccessTokenIdentify
 * @property-read mixed $accessToken
 */
class OneId extends Component
{

    public $authorizationUrl;

    public $clientId;

    public $clientSecret;

    public $scope;

    public $responseType;

    public $state;

    public $redirectUrl;

    public $grantType;


    /**
     * @return mixed
     */
    public function getAuthorizationUrl()
    {
        return $this->authorizationUrl;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return mixed
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @return mixed
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    protected function getOneAccessTokenIdentify()
    {
        return 'one_access_token_identify';
    }

    public function getLoginLink()
    {
        return $this->getAuthorizationUrl().'?'.http_build_query([
                'client_id' => $this->getClientId(),
                'redirect_uri' => $this->getRedirectUrl(),
                'scope' => $this->getScope(),
                'response_type' => $this->getResponseType(),
                'state' => $this->getState()
            ]);
    }

    protected function getAccessToken()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($this->getAuthorizationUrl())
            ->setData([
                'grant_type' =>  $this->getGrantType(),
                'client_id'  =>  $this->getClientid(),
                'client_secret' => $this->getClientSecret(),
                'code' => Yii::$app->request->get('code'),
                'scope' => $this->getScope(),
                'redirect_uri' => $this->getRedirectUrl()
            ])
            ->send();
        if ($response->isOk) {
            return $response->getData()['access_token'];
        }
    }

    public function getUserData()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($this->getAuthorizationUrl())
            ->setData([
                'grant_type' =>  $this->getOneAccessTokenIdentify(),
                'client_id'  =>  $this->getClientid(),
                'client_secret' => $this->getClientSecret(),
                'scope' => $this->getScope(),
                'access_token' => $this->getAccessToken()
            ])
            ->send();
        if ($response->isOk) {
            return $response->getData();
        }
    }

}
