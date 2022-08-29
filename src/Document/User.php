<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @MongoDB\Document
 */
class User
{

    /**
     * @MongoDB\Id
     * @SerializedName("id")
     * @Groups({"me"})
     */
    protected string $id;
    /**
     * @MongoDB\Field(type="string")
     * @SerializedName("cognito_id")
     * @Groups({"me"})
     */
    protected string $cognitoId;
    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\Index(unique=true)
     * @SerializedName("email")
     * @Groups({"me"})
     */
    private string $email;
    /**
     * @MongoDB\Field(type="date")
     * @SerializedName("last_login"))
     * @Groups({"me"})
     */
    private DateTime $lastLogin;
    /**
     * @MongoDB\Field(type="date")
     * @SerializedName("first_login")
     * @Groups({"me"})
     */
    private DateTime $firstLogin;
    /**
     * @MongoDB\Field(name="is_subscribed_to_newsletter", type="bool")
     * @SerializedName("is_subscribed_to_newsletter")
     * @Groups({"me"})
     */
    private bool $is_subscribed_to_newsletter;

    function __construct()
    {
        $this->setIsSubscribedToNewsletter(false);
    }

    /**
     * @param bool $is_subscribed_to_newsletter
     */
    public function setIsSubscribedToNewsletter(bool $is_subscribed_to_newsletter): User
    {
        $this->is_subscribed_to_newsletter = $is_subscribed_to_newsletter;
        return $this;
    }

    /**
     * @return string
     */
    public function getCognitoId(): string
    {
        return $this->cognitoId;
    }

    /**
     * @param string $cognitoId
     * @return User
     */
    public function setCognitoId(string $cognitoId): User
    {
        $this->cognitoId = $cognitoId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSubscribedToNewsletter(): bool
    {
        return $this->is_subscribed_to_newsletter;
    }

    /**
     * @return DateTime
     */
    public function getFirstLogin(): DateTime
    {
        return $this->firstLogin;
    }

    /**
     * @param DateTime $firstlogin
     */
    public function setFirstLogin(DateTime $firstlogin): User
    {
        $this->firstLogin = $firstlogin;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastLogin(): DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     */
    public function setLastLogin(DateTime $lastLogin): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
