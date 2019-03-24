<?php

namespace App\Controller\Rest;
use App\Entity\User;
use Negotiation\Exception\InvalidArgument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;


class SecurityController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"users"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request) :View
    {
        $user = new User();
        $encoder = $this->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $user->getPassword());
        $user->setUsername($request->get('username'));
        $user->setPassword($encoded);
        $user->setEmail($request->get('email'));
        $user->setRoles(['ROLES_USER']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return View::create($user, Response::HTTP_CREATED);
    }
    /**
     *
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     *
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    private function updateUser(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('App:User')
            ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }

        // Si l'utilisateur veut changer son mot de passe
        if (!empty($user->getPassword())) {
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->merge($user);
        $entityManager->flush();
        return $user;

    }

    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
    /**
     * @Rest\Post("/login")
     */
    public function login(Request $request)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
//        var_dump($request->get('username'));die();
        $user = $userRepository->findByUsername($request->get('username'));

        if (!$user) {
            throw new InvalidArgument('Erreur');
        }
        return View::create($user, Response::HTTP_OK);



    }
}
