<?php
/**
 * User: rufog
 * Date: 4/5/12
 * Time: 3:36 PM
 */
namespace Ost\ManyToOneBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ObjectToNumberTransformer implements DataTransformerInterface{
    private $om;
    private $options;

    public function __construct(ObjectManager $om, array $options){
        $this->om = $om;
        $this->options = $options;
    }

    public function transform($issue)
    {
        if (null === $issue) {
            return "";
        }

        return $issue->getId();
    }

    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $issue = $this->om
            ->getRepository($this->options['entity'])
            ->findOneBy(array('id' => $number))
        ;

        if (null === $issue) {
            throw new TransformationFailedException(sprintf(
                'Cant find object "%s"',
                $number
            ));
        }

        return $issue;
    }
}
