<?php

namespace BackendUserBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UsersController
 * @package BackendUserBundle\Controller
 */
class UsersController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"user"})
     */
    public function getUsersAllAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"user"})
     */
    public function getUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found');
        }

        $view = $this->view($user, 200);
        return $this->handleView($view);
    }
}
