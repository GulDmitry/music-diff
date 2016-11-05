<?php

namespace BackendUserBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Rest\Version("v1")
 * or if you support multiple versions in this controller ({"v1", "v1.1", "v2"})
 * Class UsersController
 * @package BackendUserBundle\Controller
 */
class UsersController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Rest\View(serializerGroups={"user"})
     */
    public function getUsersAllAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        $view = $this->view($users, 200);
        // TODO: The annotation does not work.
        $view->setContext($view->getContext()->setGroups(['user']));
        return $this->handleView($view);
    }

    /**
     * @param $id
     * @Rest\View(serializerGroups={"user"})
     * @return \Symfony\Component\HttpFoundation\Response
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
