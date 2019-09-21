<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {
    /**
     * @Assert\Email(groups={"Default","validation"})
     * @Assert\NotBlank(groups={"Default","validation"})
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
    public function setPassword($password) {
        $this->passwordHash = $password;
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
     * Getter for password hash
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->passwordHash;
    }

    /**
     * Not needed for modern algorithms
     *
     * @return string
     */
    public function getSalt()
    {
        return;
    }

    /**
     * Getter for symfony security bundle
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    
    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
        $this->passwordHash = null;
    }
    
    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles() {
        return [];
    }
}