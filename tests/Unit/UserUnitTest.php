<?php

namespace Jsdecena\Baserepo\Test\Unit;

use Jsdecena\Baserepo\Models\User;
use Jsdecena\Baserepo\Repositories\UserRepository;
use Jsdecena\Baserepo\Test\TestCase;

class UserUnitTest extends TestCase
{
    /** @test */
    public function it_can_list_the_users()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret'
        ];

        $user = $this->createUser($data);

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

        $user = $this->createUser($data);

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

        $user = $this->createUser($data);

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

        $user = $this->createUser($data);

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

        $user = $this->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    private function createUser($data)
    {
        $userRepo = new UserRepository(new User);
        return $userRepo->createUser($data);
    }
}