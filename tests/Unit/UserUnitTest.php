<?php

namespace Jsdecena\Baserepo\Test\Unit;

use Jsdecena\Baserepo\Models\User;
use Jsdecena\Baserepo\Repositories\UserRepository;
use Jsdecena\Baserepo\Test\TestCase;
use Jsdecena\Baserepo\Transformers\UserTransformer;

class UserUnitTest extends TestCase
{
    /** @test */
    public function it_can_transform_paginated_collection()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ];

        factory(User::class)->create($data);

        $userRepo = new UserRepository(new User);

        $perPage = 10;
        $transform = $userRepo->processPaginatedResults(User::paginate($perPage), new UserTransformer, 'users');
        $transformData = $transform->toArray();
        $response = $transformData['data'];

        collect($response)->each(function ($item) use ($data) {
            $this->assertEquals($data['name'], $item['attributes']['name']);
            $this->assertEquals($data['email'], $item['attributes']['email']);
        });

        $paginate = $transformData['meta']['pagination'];

        $this->assertEquals(1, $paginate['total']);
        $this->assertEquals(1, $paginate['count']);
        $this->assertEquals($perPage, $paginate['per_page']);
        $this->assertEquals(1, $paginate['current_page']);
        $this->assertEquals(1, $paginate['total_pages']);
    }

    /** @test */
    public function it_can_transform_user_collection_and_paginate_it()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ];

        factory(User::class)->create($data);

        $userRepo = new UserRepository(new User);

        $transform = $userRepo->processCollectionTransformer($userRepo->listUsers(), new UserTransformer, 'users');
        $transformData = $transform->toArray();

        $response = $transformData['data'];

        collect($response)->each(function ($item) use ($data) {
            $this->assertEquals($data['name'], $item['attributes']['name']);
            $this->assertEquals($data['email'], $item['attributes']['email']);
        });

        $paginate = $transformData['meta']['pagination'];

        $this->assertEquals(1, $paginate['total']);
        $this->assertEquals(1, $paginate['count']);
        $this->assertEquals(25, $paginate['per_page']);
        $this->assertEquals(1, $paginate['current_page']);
        $this->assertEquals(1, $paginate['total_pages']);
    }

    /** @test */
    public function it_can_transform_the_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ];

        $user = factory(User::class)->create($data);

        $userRepo = new UserRepository(new User);
        $transform = $userRepo->processItemTransformer($user, new UserTransformer, 'users');

        $response = $transform->toArray()['data'];

        $this->assertEquals($response['type'], 'users');
        $this->assertEquals($response['attributes']['name'], $data['name']);
        $this->assertEquals($response['attributes']['email'], $data['email']);
    }
    
    /** @test */
    public function it_can_list_the_users()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret'
        ];

        $user = factory(User::class)->create($data);

        $userRepo = new UserRepository(new User);
        $users = $userRepo->listUsers();

        $users->each(function (User $item) use ($user) {
            $this->assertEquals($user->name, $item->name);
            $this->assertEquals($user->email, $item->email);
        });
    }

    /** @test */
    public function it_can_delete_the_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret'
        ];

        $user = factory(User::class)->create($data);

        $userRepo = new UserRepository($user);
        $deleted = $userRepo->deleteUser();

        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret'
        ];

        $user = factory(User::class)->create($data);

        $update = [
            'name' => 'Jane Doe'
        ];

        $userRepo = new UserRepository($user);
        $updated = $userRepo->updateUser($update);

        $this->assertTrue($updated);
    }

    /** @test */
    public function it_can_show_the_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret'
        ];

        $user = factory(User::class)->create($data);

        $userRepo = new UserRepository(new User);
        $found = $userRepo->findUserById($user->id);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->name, $found->name);
        $this->assertEquals($user->email, $found->email);
    }
    
    /** @test */
    public function it_can_create_a_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret'
        ];

        $userRepo = new UserRepository(new User);
        $user = $userRepo->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }
}