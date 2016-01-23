
This package is a Laravel 5 service provider which provides support for Steam OpenID and is very easy to integrate with any project that requires Steam authentication.

## Installation Via Composer
Add this to your `composer.json` file, in the require object:

```javascript
"eliuflorez/steam-auth": "1.*"
```

After that, run `composer install` to install the package.

Add the service provider to `app/config/app.php`, within the `providers` array.

```php
'providers' => [
	// ...
	EliuFlorez\SteamAuth\SteamServiceProvider::class,
]
```

Lastly, publish the config file.

```
php artisan vendor:publish
```
## Usage example
In `config/steam-auth.php`
```php
return [

    /*
     * Redirect URL after login
     */
    'redirect_url' => '/login',
	
    /*
     *  API Key (http://steamcommunity.com/dev/apikey)
     */
    'api_key' => 'Your API Key'

];

```
In `routes.php`
```php
get('login', 'AuthController@login');
```
In `AuthController`
```php
namespace App\Http\Controllers;

use EliuFlorez\SteamAuth\SteamAuth;
use App\User;
use Auth;

class AuthController extends Controller
{

    /**
     * @var SteamAuth
     */
    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    public function login()
    {
        if ($this->steam->validate()) { 
            $info = $this->steam->getUserInfo();
            if (! is_null($info)) {
                $user = User::where('steamid', $info->getSteamID64())->first();
                if (!is_null($user)) {
                    Auth::login($user, true);
                    return redirect('/'); // redirect to site
                }else{
                    $user = User::create([
                        'username' => $info->getNick(),
                        'avatar'   => $info->getProfilePictureFull(),
                        'steamid'  => $info->getSteamID64()
                    ]);
                    Auth::login($user, true);
                    return redirect('/'); // redirect to site
                }
            }
        } else {
            return $this->steam->redirect(); // redirect to Steam login page
        }
    }
}

```
