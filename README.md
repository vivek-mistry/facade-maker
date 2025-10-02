## Laravel Facade Maker

A Laravel package that helps you to make the Facade & ready the facade structure.


## 🛠️ Installation

Install the package via Composer:
```bash
composer require vivek-mistry/facade-maker
```

## How to generate the Facade?
```bash
php artisan app:facade-maker

// Ask you for FacadeName and FacadeService
// FacadeName : FileUpload
// FacadeService : CommonFileUpload
```

So using above two files created at app/Facades & app/Facades/Services
<ul>
<li>FileUpload.php</li>
<li>CommonFileUpload.php</li>
</ul>



## Register Your Facades in the AppServiceProvider

    
```php
    $this->app->singleton("commonfileupload", function ($app) {
        return app(CommonFileUpload::class);
    });
```    

## HOW TO USE IN YOUR CONTROLLER? 

For example : 

```php
class UserController extends Controller
{

    public function index($request)
    {
        CommonFileUpload::fileupload($request->file)
    }
}
```

## Testing
```php
composer test
```

## Credits

- [Vivek Mistry](https://github.com/vivek-mistry) - Project creator and maintainer

## License
MIT License. See [LICENSE](https://github.com/vivek-mistry/facade-maker/blob/main/LICENSE) for details.