<?php

namespace AppBundle\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mailing_has_tag
 *
 * @ORM\Table(name="mailing_has_tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Entity\Mailing_has_tagRepository")
 */
class Mailing_has_tag
{

    /**
     * @var int
     *@ORM\Id
     * @ORM\Column(name="mailing_mailingId", type="integer")
     */
    private $mailingMailingId;

    /**
     * @var int
     *
     * @ORM\Column(name="tag_tagId", type="integer")
     */
    private $tagTagId;


    /**
     * Set mailingMailingId
     *
     * @param integer $mailingMailingId
     *
     * @return Mailing_has_tag
     */
    public function setMailingMailingId($mailingMailingId)
    {
        $this->mailingMailingId = $mailingMailingId;

        return $this;
    }

    /**
     * Get mailingMailingId
     *
     * @return int
     */
    public function getMailingMailingId()
    {
        return $this->mailingMailingId;
    }

    /**
     * Set tagTagId
     *
     * @param integer $tagTagId
     *
     * @return Mailing_has_tag
     */
    public function setTagTagId($tagTagId)
    {
        $this->tagTagId = $tagTagId;

        return $this;
    }

    /**
     * Get tagTagId
     *
     * @return int
     */
    public function getTagTagId()
    {
        return $this->tagTagId;
    }
}

