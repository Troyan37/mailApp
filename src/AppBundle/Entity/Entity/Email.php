<?php

namespace AppBundle\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Entity\EmailRepository")
 */
class Email
{
    /**
     * @var int
     *
     * @ORM\Column(name="emailId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $emailId;

    /**
     * @var string
     *
     * @ORM\Column(name="emailAddress", type="string", length=45)
     */
    private $emailAddress;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->emailId;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return Email
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

