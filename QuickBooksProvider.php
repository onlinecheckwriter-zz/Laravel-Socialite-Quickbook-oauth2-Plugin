<?php

namespace Laravel\Socialite\Two;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;

class QuickBooksProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['com.intuit.quickbooks.accounting', 'openid','profile','email','phone','address'];

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The fields that are included in the profile.
     *
     * @var array
     */
    protected $fields = [
       'code','realmId','state'
    ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(env("QUICKBOOKS_AUTHORIZATION_ENDPOINT"), $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return env("QUICKBOOKS_TOKEN_ENDPOINT") ;
    }



    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {
		$authrization = "Basic " . base64_encode(env('QUICKBOOKS_KEY').":".env('QUICKBOOKS_SECRET'));
		
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }



    public function getAccessTokenResponse($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

        $authrization = "Basic " . base64_encode(env('QUICKBOOKS_KEY').":".env('QUICKBOOKS_SECRET'));

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json',"Authorization"=>$authrization,'Content-Type'=>'application/x-www-form-urlencoded',"Content-Type: application/x-www-form-urlencoded"],
            "body"=>"grant_type=authorization_code&code=$code&redirect_uri=$this->redirectUrl"


        ]);


        return json_decode($response->getBody(), true);
    }



    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {



       $response = $this->getHttpClient()->get(env("QUICKBOOKS_USERINFO_ENDPOINT"), [

            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'name' => $user['givenName'].$user['familyName'],'email'=>$user["email"]
        ]);
    }

    /**
     * Set the user fields to request from LinkedIn.
     *
     * @param  array  $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }
}
