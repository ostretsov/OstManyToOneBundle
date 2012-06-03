<?php
/**
 * User: rufog
 * Date: 5/21/12
 * Time: 1:20 PM
 */
namespace Ost\ManyToOneBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;
use MakerLabs\PagerBundle\Pager;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;

class ManyToOneController extends Controller{
    /**
     * @Route("/ost/manyToOne/getTable", name="ost_manyToOne_getTable")
     * @Template()
     */
    public function getBindedTableAction(){
        $page = $this->getRequest()->get('page', 1);
        $query = $this->getRequest()->get('query');

        $formClass = $this->getRequest()->get('formClass');
        $formItem = $this->getRequest()->get('formItem');

        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new $formClass());
        $formItemTypes = $form->get($formItem)->getTypes();
        $manyToOneSelectorType = array_pop($formItemTypes);
        $options = $manyToOneSelectorType->getOptions();

        $defaultValues = $this->container->getParameter('ost.many_to_one.parameters');
        $options = array_merge($defaultValues, $options);

        $queryClosure = $options['query_builder'];
        $querySearchClosure = $options['query_builder_search'];
        if(!($queryClosure instanceof \Closure && $querySearchClosure instanceof \Closure)){
            throw new UnexpectedTypeException($queryClosure, '\Closure');
        }

        $dq = $queryClosure($em->getRepository($options['entity']));
        if($query){
            $dq = $querySearchClosure($dq, $query);
        }

        $adapter = new DoctrineOrmAdapter($dq);
        $pager = new Pager($adapter, array('page' => $page, 'limit' => $options['items_per_page']));
        $entities = $pager->getResults();

        return array('entities' => $entities, 'pager' => $pager, 'query' => $query, 'list_template' => $options['list_template']);
    }


    /**
     * @Route("/ost/manyToOne/toString", name="ost_manyToOne_toString")
     * @Template()
     */
    public function toStringAction(){
        $item = $this->getDoctrine()->getEntityManager()->getRepository('OstUserBundle:User')->find($this->getRequest()->get('itemId'));
        if(!$item){
            throw NotFoundHttpException("Can't find object");
        }

        return array('item' => $item);
    }
}
