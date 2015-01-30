<?php

namespace Acme\SubscribeBundle\Controller;

use Acme\SubscribeBundle\Entity\Subscriber;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/subscribe")
 */

class DefaultController extends Controller
{
    /**
     * @Route("/save", name="save_subscriber")
     * @Method("POST")
     */
    public function saveAction(Request $request)
    {
        $arr = array();
        if ($request->isXmlHttpRequest()) {
            $email = $request->request->get('email', '');
            $email = trim($email);
            if (!empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('AcmeSubscribeBundle:Subscriber')->findOneByEmail($email);
                if (!$entity) {
                    $entity = new Subscriber();
                    $entity->setName($email);
                    $entity->setEmail($email);
                    $entity->setStatus(Subscriber::STATUS_ACTIVE);
                    $em->persist($entity);
                    $em->flush();
                }
                $arr['success'] = true;
            } else {
                $arr['error'] = 'Email is empty';
            }
        } else {
            $arr['error'] = 'You are bot?';
        }

        return new JsonResponse($arr);
    }
}
