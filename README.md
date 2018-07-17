# Base Repository Package

[![Build Status](https://travis-ci.org/jsdecena/baserepo.svg?branch=master)](https://travis-ci.org/jsdecena/baserepo)
[![Latest Stable Version](https://poser.pugx.org/jsdecena/baserepo/v/stable)](https://packagist.org/packages/jsdecena/baserepo)
[![Total Downloads](https://poser.pugx.org/jsdecena/baserepo/downloads)](https://packagist.org/packages/jsdecena/baserepo)
[![License](https://poser.pugx.org/jsdecena/baserepo/license)](https://packagist.org/packages/jsdecena/baserepo)

## Install

- Run in your terminal `composer require jsdecena/baserepo`

- Add the base service provider in your `config/app.php` file like this:

```php
    'providers' => [

        /*
         * Package Service Providers...
         */
        Jsdecena\Baserepo\BaseRepositoryProvider::class,
    ],
```

- In your repository class, extend it so you can use the methods readily available.

```php

namespace App\Repositories;

use App\User;
use Illuminate\Database\QueryException;
use Jsdecena\Baserepo\BaseRepository;

class UserRepository extends BaseRepository {
    
    public function __construct(User $user) 
    {
        parent::__construct($user);
    }
    
    public function createUser(array $data) : User
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new \Exception($e);
        }
    }
}
```

- Then, use it in your controller.

```php

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;

class MyController extends Controller {
    
    public function store(Request $request)
    {
        // do data validation
        
        $userRepo = new UserRepository(new User);
        $user = $userRepo->createUser($request->all());

        return response()->json($data, 201);
    }
}
```
