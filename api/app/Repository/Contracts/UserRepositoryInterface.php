<?php

namespace App\Repository\Contracts;

interface UserRepositoryInterface
{
    public function get(int $id);

    public function getAll(array $filter);
}
