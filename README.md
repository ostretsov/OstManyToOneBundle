OstManyToOneBundle - Many to One relation form widget
=====================================================

Installation
------------

* Add lines in deps file:
```
	[PagerBundle]
	    git=https://github.com/makerlabs/PagerBundle.git
	    target=/bundles/MakerLabs/PagerBundle

	[OstManyToOneBundle]
	    git=https://ruFog@github.com/ruFog/OstManyToOneBundle.git
	    target=/bundles/Ost/ManyToOneBundle
```
* Add object init in AppKernel.php:
```
	new \Ost\ManyToOneBundle\OstManyToOneBundle(),
```
* Add autoload.php:
```
	'Ost' => __DIR__.'/../vendor/bundles',
```
* And run:
```
	php bin/vendors install
```

Usage
-----

In buildForm function:
``` php
->add('requestedBy', 'object_many_to_one_selector', array(
	'form_class' => addslashes(__CLASS__),
        'label' => 'Requested By',
        'entity' => 'OstUserBundle:User',
        'list_template' => 'OstUserBundle:User:many_to_one_list.html.twig',
        'query_builder' => function(EntityRepository $er) {
        	return $er->createQueryBuilder('u')
                    ->where('u.roles not like :role_manager')
                    ->andWhere('u.roles not like :role_admin')
                    ->setParameter('role_manager', '%MANAGER%')
                    ->setParameter('role_admin', '%SUPER%')
                    ->orderBy('u.username', 'ASC')
                ;
        },
        'query_builder_search' => function(QueryBuilder $qb, $query){
                return $qb->andWhere('u.username like :query')
                    ->setParameter('query', '%'.$query.'%')
                ;
        },
        'items_per_page' => 5
))
```

many_to_one_list.html.twig example:
``` twig
<table class="table table-striped table-bordered table-condensed">
    {% for entity in entities %}
    <tr itemId="{{ entity.id }}" style="cursor: pointer;">
        <td>{{ entity.id }}</td>
        <td>{{ entity.username }}</td>
        <td>{{ entity.getRolesString() }}</td>
    </tr>
    {% endfor %}
</table>
```

``OstUserBundle:User`` (or any other) entity must contain id unique primary key and function ``__toString()``.

Default value of ``items_per_page`` is 10.
