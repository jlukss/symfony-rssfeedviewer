<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SignUpController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/user/signup")
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if ($this->userRepository->getByEmail($user->getEmail()) !== null) {
            $data = [
                'type' => 'error',
                'message' => 'User already exists',
                'errors' => []
            ];

            return new JsonResponse($data, 400);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->userRepository->save($user);

            return new JsonResponse([
                'type' => 'success',
                'message' => 'User saved'
            ], 201);
        }

        $errors = $this->getErrorsFromForm($form);

        $data = [
            'type' => 'error',
            'message' => 'Please fix errors in the submitted form',
            'errors' => $errors
        ];

        return new JsonResponse($data, 400);
    }

    /**
     * @Route("/user/validate")
     */
    public function validateEmail(Request $request, ValidatorInterface $validator)
    {
        $email = $request->query->get('email');
        $user = new User();
        $user->setEmail($email);

        $errors = $validator->validate($user, null, ['validation']);

        if (count($errors) == 0) {
            $user = $this->userRepository->getByEmail($email);
            
            return new JsonResponse($user === null, 200);
        }

        return new JsonResponse(false, 400);
    }

    private function getErrorsFromForm($form)
    {
        $errors = array();
        foreach ($form->getErrors(true, true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }
        return $errors;
    }
}
