<?php

namespace Jsdecena\Baserepo\Transformers;

use Jsdecena\Baserepo\Models\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }
}