<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/7/14
 * Time: 12:56 AM
 */

namespace Acme\SubscribeBundle\Service;

use Acme\MainBundle\Entity\InProces;
use Acme\SubscribeBundle\Entity\Subscriber;
use Acme\SubscribeBundle\Entity\TemplateLetter;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Swift_Message;

class Sender
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var
     */
    protected $mailer;

    /**
     * @var
     */
    protected $em;

    /**
     * @param $em
     * @param Container $container
     * @param $mailer
     */
    public function __construct($em, Container $container, $mailer)
    {
        $this->container = $container;
        $this->mailer = $mailer;
        $this->em = $em;
    }


    public function send()
    {
        $em = $this->em;
        try {
            $j = 0;
            $entities = $em->getRepository('AcmeSubscribeBundle:TemplateLetter')->findAll();
            foreach ($entities as $entity) {/** @var TemplateLetter $entity */
                $subscribers = $entity->getSubscribers();
                foreach ($subscribers as $sub) {/** @var $sub Subscriber $entity */
                    if ($j > 30) {
                        break;
                    }
                    $mail = trim($sub->getEmail());
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        $failures = array();
                        try {
                            $html = $this->prepareHtml($entity->getHtml(), $sub);

                            $message = \Swift_Message::newInstance()
                                ->setSubject($entity->getSubject())
                                ->setFrom($entity->getFromWho(), $entity->getName())
                                ->setTo($mail)
                                ->setBody($html, 'text/html');

                            if ($this->mailer->send($message, $failures)) {
                                //Succes send
                                echo 'Done';
                            } else {
//                                $entity->setErrors($entity->getErrors() . ' - ' . join(PHP_EOL, $failures) . ' - ' . $mail);
                            }

                            $entity->removeSubscriber($sub);
                            $sub->removeLetter($entity);
                            $em->persist($entity);
                            $em->persist($sub);
                        } catch (\Exception $e) {
                            $entity->setErrors($entity->getErrors() . ' - ' . $e->getMessage() . ' - ' . $mail);
                        }
                    }
                }
            }
            $em->flush();
        } catch(\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function prepareHtml($html, Subscriber $subscriber)
    {
        $html = str_replace('{{email}}', $subscriber->getEmail(), $html);
        $html = str_replace('{{email_hash}}', md5($subscriber->getEmail()), $html);

        return $html;
    }
} 