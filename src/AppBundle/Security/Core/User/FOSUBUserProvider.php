<?php
namespace AppBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set' . ucfirst($service);
            $setter_id = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($username);
            $user->setRealName($response->getRealName());
            $user->setEmail($response->getEmail() ?? 'example@example.com');
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());
        return $user;
    }

//    /**
//     * {@inheritDoc}
//     */
//    public function connect(UserInterface $user, UserResponseInterface $response)
//    {
//        // get property from provider configuration by provider name
//        // , it will return `google_id` in that case (see service definition below)
//        $property = $this->getProperty($response);
//        $username = $response->getUsername(); // get the unique user identifier
//
//        //we "disconnect" previously connected users
//        $existingUser = $this->userManager->findUserBy(array($property => $username));
//        if (null !== $existingUser) {
//            // set current user id and token to null for disconnect
//            // ...
//
//            $this->userManager->updateUser($existingUser);
//        }
//        // we connect current user, set current user id and token
//        // ...
//        $this->userManager->updateUser($user);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
//    {
//        $userEmail = $response->getEmail();
//        $user = $this->userManager->findUserByEmail($userEmail);
//
//        // if null just create new user and set it properties
//        if (null === $user) {
//
//            $this->userManager->createUser();
//            $username = $response->getRealName();
//            $user = new User();
//            $user->setUsername($username);
//
//            // ... save user to database
//
//            return $user;
//        }
//        // else update access token of existing user
//        $serviceName = $response->getResourceOwner()->getName();
//        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
//        $user->$setter($response->getAccessToken());//update access token
//
//        return $user;
//    }
}
