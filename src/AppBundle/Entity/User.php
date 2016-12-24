<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @Exclude
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     * @Expose
     */
    private $balance = 0;

    /**
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    private $googleId;

    /**
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    private $googleAccessToken;

    /**
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    private $facebookId;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    private $facebookAccessToken;

    /**
     * @ORM\Column(name="real_name", type="string", nullable=true)
     */
    private $realName;

    /**
     * Set balance.
     * @param integer $balance
     * @return User
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance.
     * @return integer|null
     */
    public function getBalance(): ?int
    {
        return $this->balance;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId(string $googleId)
    {
        $this->googleId = $googleId;
    }

    /**
     * @return string|null
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * @param string $googleAccessToken
     */
    public function setGoogleAccessToken(string $googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;
    }

    /**
     * @return string|null
     */
    public function getGoogleAccessToken(): ?string
    {
        return $this->googleAccessToken;
    }

    /**
     * @param string $facebookId
     */
    public function setFacebookId(string $facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string|null
     */
    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookAccessToken
     */
    public function setFacebookAccessToken(string $facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return string|null
     */
    public function getFacebookAccessToken(): ?string
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param string $realName
     */
    public function setRealName(string $realName)
    {
        $this->realName = $realName;
    }

    /**
     * @return string|null
     */
    public function getRealName(): ?string
    {
        return $this->realName;
    }
}
