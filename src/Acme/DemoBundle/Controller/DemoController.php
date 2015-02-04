<?php

namespace Acme\DemoBundle\Controller;

use Acme\MainBundle\Entity\Post;
use Acme\SubscribeBundle\Entity\Subscriber;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\DemoBundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class DemoController extends Controller
{

    /**
     * @Route("/dem_array/{page}", name="_dem_array")
     * @Template()
     */
    public function demArrayAction(Request $request, $page)
    {
        $ar = $this->getArray();

        $em = $this->getDoctrine()->getManager();

        foreach ($ar as $k=>$email) {
            if ($k < (1000*$page) && $k >= ((1000*$page)-1000)) {
                $email = trim($email);
                $m = $em->getRepository('AcmeSubscribeBundle:Subscriber')->findOneByEmail($email);
                if ($m) {
                    continue;
                }
                $m = new Subscriber();
                $m->setEmail($email);
                $m->setName($email);
                $em->persist($m);
                $em->flush();
            }
            if ($k >= (1000*$page)) {
                if (isset($ar[($k+1)])) {
                    return $this->redirect($this->generateUrl('_dem_array',array('page'=>($page+1))));
                } else {
                    break;
                }
            }
        }

        echo "DONE";
        exit;


        return array();
    }

    /**
     * @Route("/dem_well", name="_dem_well")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $file = $request->files->get('file', false);
            if ($file) {
                $content = $file->openFile();
                $t = '';
                foreach ($content as $line ) {
                    $t .= $line;
                }



                $crawler = new Crawler();
                $crawler->addXmlContent($t);
                $ff = $crawler->filter('channel');
                $items = $ff->filter('item');
                echo '<pre>';
                foreach ($items as $k=>$l) {
                    $title =  $l->getElementsByTagName('title')->item(0);
                    $url =  $l->getElementsByTagName('url')->item(0);
                    $content =  $l->getElementsByTagName('content')->item(0);
                    $category =  $l->getElementsByTagName('category')->item(0);

                    $nameT = $title->nodeValue;
                    $urlT = $url->nodeValue;
                    $categoryT = $category->getAttribute('nicename');
                    $contentT = $content->nodeValue;
                    $titleT = '';
                    $descriptionT = '';

                    $metas = $l->getElementsByTagName('meta');
                    foreach ($metas as $m) {
                        $val =  $m->getElementsByTagName('value')->item(0);
                        $key =  $m->getElementsByTagName('key')->item(0);
                        if ($key->nodeValue == '_su_title') {
                            $titleT = $val->nodeValue;
                        } elseif ($key->nodeValue == '_su_description') {
                            $descriptionT = $val->nodeValue;
                        }
                    }

                    $post = new Post();
                    $post->setName($nameT);
                    $post->setTitle($titleT);
                    $post->setDescription($descriptionT);
                    $post->setText($contentT);
                    $post->setUrl($urlT);

                    $entity = $em->getRepository('AcmeMainBundle:Category')->findOneByUrl($categoryT);
                    $post->setCategory($entity);
                    $em->persist($post);
                }

//                $em->flush();
//                echo 'Done';
            }
        }


        return array();
    }


    public function getArray()
    {
        $ar = array(
            );

        return $ar;
    }
}
