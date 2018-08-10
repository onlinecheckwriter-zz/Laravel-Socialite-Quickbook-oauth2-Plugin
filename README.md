# Laravel-Socialite-Quickbook-oauth2-Plugin
Add Intuit single sign-on feature in your website using laravel Socialite Quickbook 

#How to start
1. First of all install laravel sociliate using (composer require laravel/socialite)
2. Then open the .env file and add the following env variable from  quickbook

QUICKBOOKS_KEY=
QUICKBOOKS_SECRET=
QUICKBOOKS_REDIRECT_URI=
QUICKBOOKS_USERINFO_ENDPOINT=https://sandbox-accounts.platform.intuit.com/v1/openid_connect/userinfo
QUICKBOOKS_AUTHORIZATION_ENDPOINT=https://appcenter.intuit.com/connect/oauth2
QUICKBOOKS_TOKEN_ENDPOINT=https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer

3.Copy and paste QuickBooksProvider.php file under folder
  vendor/laravel/socialite/src/two/
  
  also add the following line on   vendor/laravel/socialite/src/SocialiteManger.php  Or replace the file using SocialiteManger.php  from    our github repo
  
     protected function createQuickbookDriver()
    {
        $config = $this->app['config']['services.quickbooks'];

        return $this->buildProvider(
          QuickBooksProvider::class, $config
        );
    }
    
    


Source : 
Sandbox: //https://developer.intuit.com/.well-known/openid_sandbox_configuration/

Production  : https://developer.intuit.com/.well-known/openid_configuration/


Reference:
https://developer.intuit.com/docs/00_quickbooks_online/2_build/10_authentication_and_authorization/10_oauth_2.0#/Discovery_document
