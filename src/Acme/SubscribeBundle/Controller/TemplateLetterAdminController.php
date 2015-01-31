<?php

namespace Acme\SubscribeBundle\Controller;

use Acme\SubscribeBundle\Entity\Subscriber;
use Acme\SubscribeBundle\Entity\TemplateLetter;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TemplateLetterAdminController extends CRUDController
{
    public function cloneAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $clonedObject = clone $object;  // Careful, you may need to overload the __clone method of your object
        // to set its id to null
        $clonedObject->setName($object->getName()." (Clone)");

        $this->admin->create($clonedObject);

        $this->addFlash('sonata_flash_success', 'Cloned successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }


    public function startAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        /** @var TemplateLetter $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AcmeSubscribeBundle:Subscriber')->findBy(array('status'=>Subscriber::STATUS_ACTIVE));

        foreach ($entities as $entity) {/** @var Subscriber $entity */
            $object->addSubscriber($entity);
            $entity->addLetter($object);

            $em->persist($entity);
        }

        $em->persist($object);
        $em->flush();

//        $clonedObject = clone $object;  // Careful, you may need to overload the __clone method of your object
//        // to set its id to null
//        $clonedObject->setName($object->getName()." (Clone)");
//
//        $this->admin->create($clonedObject);

        $this->addFlash('sonata_flash_success', 'Start sending');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }


}
