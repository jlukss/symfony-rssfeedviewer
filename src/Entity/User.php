<?php

namespace App\Entity;

class User {
    /**
     * User email
     *
     * @var string
     */
    private $email;

    /**
     * User password hash
     *
     * @var string
     */
    private $passwordHash;

    /**
     * Setter for email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Setter for password hash
     *
     * @param string $passwordHash
     * @return void
     */
    public function setPasswordHash($passwordHash) {
        $this->password = $passwordHash;
    }

    /**
     * Getter for email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Getter for passwordhash
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }
}