<?php
namespace App\Service;

use App\Entity\User;

class UserRepository {

    /**
     * Retrieve User by email
     *
     * @param string $email
     * @return User
     */
    public function getByEmail($email)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Save user to file
     *
     * @param User $user
     * @return boolean
     */
    public function save(User $user)
    {
        throw new \Exception('Not implemented');
    }
}