<?php

namespace Jsdecena\Baserepo\Test\Models\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Jsdecena\Baserepo\BaseRepository;
use Jsdecena\Baserepo\Test\Models\User;

class UserRepository extends BaseRepository
{
    /**
     *
     * This is only a sample repository CRUD. ** Do not use **
     *
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public function createUser(array $data) : User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function updateUser(array $data) : bool
    {
        return $this->update($data);
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function findUserById(int $id) : User
    {
        return $this->find($id);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteUser() : bool
    {
        return $this->delete();
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listUsers($columns = ['*'], string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }
}