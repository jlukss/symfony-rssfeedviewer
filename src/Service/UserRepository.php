<?php
namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserRepository implements UserProviderInterface {
    private $params;

    public function __construct(ParameterBagInterface $params = null)
    {
        $this->params = $params;
    }
    
    /**
     * Load user for Guard
     *
     * @param string $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        return $this->getByEmail($username);
    }

    /**
     * Refresh user session
     *
     * @param UserInterface $user
     * @return User
     */
    public function refreshUser(UserInterface $user) {
        return $user;
    }

    /**
     * Retrieve User by email
     *
     * @param string $email
     * @return User
     */
    public function getByEmail($email)
    {
        $handle = $this->_openFile('r');

        while (($buffer = fgets($handle, 4096)) !== false) {
            $data = explode("\t", strtok($buffer, "\n"));
            if ($data[0] == $email) {
                $user = new User();
                $user->setEmail($data[0]);
                $user->setPassword($data[1]);

                fclose($handle);
                return $user;
            }
        }

        fclose($handle);
        return null;
    }

    /**
     * Save user to file
     *
     * @param User $user
     * @return boolean
     */
    public function save(User $user)
    {
        $handle = $this->_openFile('a');
        $data = implode("\t" , [$user->getEmail(), $user->getPassword()]);
        fwrite($handle, $data . "\n");
        fclose($handle);
        return true;
    }

    /**
     * Open file for read or append
     *
     * @param string $mode
     * @return resource
     */
    private function _openFile($mode)
    {
        if (!file_exists($this->params->get('kernel.project_dir') . '/data/users.dat')) {
            $handle = fopen($this->params->get('kernel.project_dir') . '/data/users.dat', 'w+');
        } else {
            $handle = fopen($this->params->get('kernel.project_dir') . '/data/users.dat', $mode);
        }
        return $handle;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}