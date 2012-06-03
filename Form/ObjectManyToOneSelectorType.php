<?php
/**
 * User: rufog
 * Date: 5/20/12
 * Time: 1:45 PM
 */
namespace Ost\ManyToOneBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Ost\ManyToOneBundle\Form\DataTransformer\ObjectToNumberTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ObjectManyToOneSelectorType extends AbstractType{
    private $om;
    private $options;

    public function __construct(ObjectManager $om){
        $this->om = $om;
    }

    public function buildForm(FormBuilder $builder, array $options){
        $transformer = new ObjectToNumberTransformer($this->om, $options);
        $builder->appendClientTransformer($transformer);
        $this->options = $options;
    }

    public function buildView(FormView $view, FormInterface $form){
        $view->set('entity', $form->getData());
        $view->set('form_class', $this->options['form_class']);
    }

    public function getOptions(){
        return $this->options;
    }

    public function getDefaultOptions(array $options){
        return array(
            'form_class'     => null,
            'entity'    => null,
            'required'  => false,
            'items_per_page'    => 10,
            'query_builder' => null,
            'query_builder_search' => null,
            'list_template' => null
        );
    }

    public function getParent(array $options){
        return 'field';
    }

    public function getName(){
        return 'object_many_to_one_selector';
    }
}
