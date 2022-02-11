<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository
{

    public function __construct(Customer $user)
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
