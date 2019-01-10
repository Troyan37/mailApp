<?php

namespace AppBundle\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Verified_email
 *
 * @ORM\Table(name="verified_email")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Entity\Verified_emailRepository")
 */
class Verified_email
{
    /**
     * @var int
     *
     * @ORM\Column(name="verifiedId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $verifiedId;

    /**
     * @var string
     *
     * @ORM\Column(name="emailAddress", type="string", length=45)
     */
    private $emailAddress;


    /**
     * Get verifiedId
     *
     * @return int
     */
    public function getId()
    {
        return $this->verifiedId;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return Verified_email
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
}

