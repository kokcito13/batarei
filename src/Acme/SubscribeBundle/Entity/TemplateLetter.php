<?php

namespace Acme\SubscribeBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * TemplateLetter
 *
 * @ORM\Table(name="template_letters")
 * @ORM\Entity(repositoryClass="Acme\SubscribeBundle\Entity\TemplateLetterRepository")
 */
class TemplateLetter
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
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="html", type="text")
     */
    private $html;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="from_who", type="string", length=255)
     */
    private $fromWho;

    /**
     * @ORM\ManyToMany(targetEntity="Subscriber", inversedBy="letters")
     * @ORM\JoinTable(name="subscribers_letters")
     **/
    private $subscribers;

    public function __construct()
    {
        $this->subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime('now');
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
     * @return TemplateLetter
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
     * Set subject
     *
     * @param string $subject
     * @return TemplateLetter
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set html
     *
     * @param string $html
     * @return TemplateLetter
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get html
     *
     * @return string 
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return TemplateLetter
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
     * Set fromWho
     *
     * @param string $fromWho
     * @return TemplateLetter
     */
    public function setFromWho($fromWho)
    {
        $this->fromWho = $fromWho;

        return $this;
    }

    /**
     * Get fromWho
     *
     * @return string 
     */
    public function getFromWho()
    {
        return $this->fromWho;
    }

    /**
     * Add subscribers
     *
     * @param \Acme\SubscribeBundle\Entity\Subscriber $subscribers
     * @return TemplateLetter
     */
    public function addSubscriber(\Acme\SubscribeBundle\Entity\Subscriber $subscribers)
    {
        $this->subscribers[] = $subscribers;

        return $this;
    }

    /**
     * Remove subscribers
     *
     * @param \Acme\SubscribeBundle\Entity\Subscriber $subscribers
     */
    public function removeSubscriber(\Acme\SubscribeBundle\Entity\Subscriber $subscribers)
    {
        $this->subscribers->removeElement($subscribers);
    }

    /**
     * Get subscribers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    public function getLimitSubscribers()
    {
        $criteria = Criteria::create()
            ->setMaxResults(30);

        return $this->subscribers->matching($criteria);
    }
}
