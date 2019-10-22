# Base Repository Package

[![Build Status](https://travis-ci.org/jsdecena/baserepo.svg?branch=master)](https://travis-ci.org/jsdecena/baserepo)
[![Latest Stable Version](https://poser.pugx.org/jsdecena/baserepo/v/stable)](https://packagist.org/packages/jsdecena/baserepo)
[![Total Downloads](https://poser.pugx.org/jsdecena/baserepo/downloads)](https://packagist.org/packages/jsdecena/baserepo)
[![License](https://poser.pugx.org/jsdecena/baserepo/license)](https://packagist.org/packages/jsdecena/baserepo)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fjsdecena%2Fbaserepo.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fjsdecena%2Fbaserepo?ref=badge_shield)

#### Base repository is used by [Laracom](https://github.com/Laracommerce/laracom) under the hood 

## How to install

- Run in your terminal `composer require jsdecena/baserepo`

- In your repository class, extend it so you can use the methods readily available.

```php

namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;
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
    
    private $userRepository;
    
    /**
    *
    * Inject your repository or the interface here
    */
    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function index() 
    {
        $user = $this->userRepository->all();

        return response()->json($data);    
    }
    
    public function store(Request $request)
    {
        // do data validation
    
        try {
            
            $user = $this->userRepository->createUser($request->all());
    
            return response()->json($data, 201);
        
        } catch (Illuminate\Database\QueryException $e) {
            
            return response()->json([
                'error' => 'user_cannot_create',
                'message' => $e->getMessage()
            ]);        
        }
    }

    public function show($id)
    {
        // do data validation
        
        try {
            
            $user = $this->userRepository->findOneOrFail($id);
    
            return response()->json($data);
            
        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            
            return response()->json([
                'error' => 'user_no_found',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function update(Request $request, $id)
    {
        // do data validation
        
        try {
            
            $user = $this->userRepository->findOneOrFail($id);
            
            // Create an instance of the repository again 
            // but now pass the user object. 
            // You can DI the repo to the controller if you do not want this.
            $userRepo = new UserRepository($user);
            $userRepo->update($request->all())
    
            return response()->json($data);
            
        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            
            return response()->json([
                'error' => 'user_no_found',
                'message' => $e->getMessage()
            ]);            
            
        } catch (Illuminate\Database\QueryException $e) {
            
            return response()->json([
                'error' => 'user_cannot_update',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id)
    {
        // do data validation
        
        try {
            
            $user = $this->userRepository->findOneOrFail($id);
            
            // Create an instance of the repository again 
            // but now pass the user object. 
            // You can DI the repo to the controller if you do not want this.
            $userRepo = new UserRepository($user);
            $userRepo->delete()
    
            return response()->json($data);
            
        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            
            return response()->json([
                'error' => 'user_no_found',
                'message' => $e->getMessage()
            ]);            
            
        } catch (Illuminate\Database\QueryException $e) {
            
            return response()->json([
                'error' => 'user_cannot_delete',
                'message' => $e->getMessage()
            ]);
        }
    }    
    
}
```

# Author

[Jeff Simons Decena](https://jsdecena.me)


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fjsdecena%2Fbaserepo.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fjsdecena%2Fbaserepo?ref=badge_large)
