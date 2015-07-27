<?php

namespace Acme\MainBundle\Controller;

use Acme\MainBundle\Entity\PostRepository;
use Acme\MainBundle\Service\Perelink;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Entity\Post;
use Acme\MainBundle\Form\PostType;

/**
 * Post controller.
 *
 * @Route("/")
 */
class PostController extends Controller
{
    /**
     * @Route("/offers/text", name="get_text_offer")
     * @Method("GET")
     */
    public function getOfferTextAction(Request $request)
    {
        $resp = array();
        $resp['success'] = false;
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $adsOffers = $em->getRepository('AdsOfferBundle:OfferText')->findBy(array("status"=>true));
            if (!empty($adsOffers)) {
                $offer = $adsOffers[array_rand($adsOffers)];

                $resp['success'] = true;
                $resp['data'] = array(
                    'title' => $offer->getTitle(),
                    'shortText' => $offer->getShortText(),
                    'text' => $offer->getText()
                );
            }
        } else {
            $resp['error'] = "You are robot?";
            throw $this->createNotFoundException('Данную статью мы неможем найти!');
        }

        return new JsonResponse($resp);
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{category_url}/{post_url}", name="client_post_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($category_url, $post_url)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AcmeMainBundle:Category')->findOneByUrl($category_url);
        if (!$category) {
            throw $this->createNotFoundException('Данную статью в этой категории мы неможем найти!');
        }

        /** @var PostRepository $postRepo */
        $postRepo = $em->getRepository('AcmeMainBundle:Post');
        $entity = $postRepo->findOneBy(
            array(
                'category' => $category,
                'url' => $post_url
            )
        );

        if (!$entity) {
            throw $this->createNotFoundException('Данную статью мы неможем найти!');
        }

        $entities = $postRepo->findOther($entity);
        
        $entity->oneMoreView();
        $em->persist($entity);
        $em->flush();

        $postEdit = $this->get('post.edit');

        $text = $entity->getText();
        $text = $postEdit->setAdvertising($text);

        $cacheKey = 'post_pereink_'.$entity->getId();
        $cache = $this->get('cache.m');
        $result = $cache->fetch($cacheKey);
        if (!$result) {
            $baseurl = $this->getRequest()->getScheme() .
                '://' . $this->getRequest()->getHttpHost() .
                $this->getRequest()->getBasePath().
                $this->getRequest()->getPathInfo();

            /** @var Perelink $perelink */
            $perelink = $this->get('binet.perelink');
            $perelink->getInfo($baseurl);
            $text = $perelink->updateText($text);
            $links = $perelink->getLinksAfter();

            $cache->save($cacheKey, json_encode(array(0=>$text, 1=>$links)), (2*24*60*60));
        } else {
            $result = json_decode($result, true);
            $text = $result[0];
            $links = $result[1];
        }

        list($text, $links) = $postEdit->setReadMore($text, $links);
        $entity->setText($text);

        $contents = $postEdit->setContents($text);

        return array(
            'entity'        => $entity,
            'entities'      => $entities,
            'links' => $links,
            'contents' => $contents
        );
    }
}
