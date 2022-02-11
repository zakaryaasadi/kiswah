<?php

namespace App\Repositories;

use App\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->user = $user;
    }

    public function getUserByUuid(string $uuid)
    {
        return $this->user->getUserByUuid($uuid);
    }
    
    public function availableDrivers(){
        return $this->user->availableDriver();
    }
}
