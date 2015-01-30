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
                /** @var Subscriber|null $entity */
                $entity = $em->getRepository('AcmeSubscribeBundle:Subscriber')->findOneByEmail($email);
                if (!$entity) {
                    $entity = new Subscriber();
                    $entity->setName($email);
                    $entity->setEmail($email);
                    $entity->setStatus(Subscriber::STATUS_ACTIVE);
                } else {
                    $entity->setStatus(Subscriber::STATUS_ACTIVE);
                }
                $em->persist($entity);
                $em->flush();
                $arr['success'] = true;
            } else {
                $arr['error'] = 'Email is empty';
            }
        } else {
            $arr['error'] = 'You are bot?';
        }

        return new JsonResponse($arr);
    }

    /**
     * @Route("/un/{hash}", name="un_subscriber")
     * @Template()
     * @Method("GET")
     */
    public function unAction(Request $request, $hash = '')
    {
        $email = '';
        $hash = trim($hash);
        if (empty($hash)) {
            throw $this->createNotFoundException('Просим нас извинить но данную страницу мы неможем найти.');
        }

        $em = $this->getDoctrine()->getManager();

        $sql = "SELECT id FROM subscribers WHERE MD5(email) = '".$hash."'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (!empty($result) && isset($result[0]) && isset($result[0]['id'])) {
            $id = (int)$result[0]['id'];
            $entity = $em->getRepository('AcmeSubscribeBundle:Subscriber')->find($id);
            $entity->setStatus(Subscriber::STATUS_DELETE);

            $em->persist($entity);
            $em->flush();

            $email = $entity->getEmail();
        } else {
            throw $this->createNotFoundException('Просим нас извинить но данную страницу мы неможем найти.');
        }

        return array(
            'email' => $email
        );
    }
}
