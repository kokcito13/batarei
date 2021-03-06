<?php

namespace Acme\SubscribeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscriber
 *
 * @ORM\Table(name="subscribers")
 * @ORM\Entity(repositoryClass="Acme\SubscribeBundle\Entity\SubscriberRepository")
 */
class Subscriber
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rejected_at", type="datetime")
     */
    private $rejectedAt;

    /**
     * @ORM\ManyToMany(targetEntity="TemplateLetter", mappedBy="subscribers")
     **/
    private $letters;

    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;
    const STATUS_CREATE = 0;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->rejectedAt = new \DateTime('now');
        $this->status = self::STATUS_ACTIVE;
        $this->letters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Subscriber
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Subscriber
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Subscriber
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Subscriber
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set rejectedAt
     *
     * @param \DateTime $rejectedAt
     * @return Subscriber
     */
    public function setRejectedAt($rejectedAt)
    {
        $this->rejectedAt = $rejectedAt;

        return $this;
    }

    /**
     * Get rejectedAt
     *
     * @return \DateTime 
     */
    public function getRejectedAt()
    {
        return $this->rejectedAt;
    }

    /**
     * Add letters
     *
     * @param \Acme\SubscribeBundle\Entity\TemplateLetter $letters
     * @return Subscriber
     */
    public function addLetter(\Acme\SubscribeBundle\Entity\TemplateLetter $letters)
    {
        $this->letters[] = $letters;

        return $this;
    }

    /**
     * Remove letters
     *
     * @param \Acme\SubscribeBundle\Entity\TemplateLetter $letters
     */
    public function removeLetter(\Acme\SubscribeBundle\Entity\TemplateLetter $letters)
    {
        $this->letters->removeElement($letters);
    }

    /**
     * Get letters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLetters()
    {
        return $this->letters;
    }
}
