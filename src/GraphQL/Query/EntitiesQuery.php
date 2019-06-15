<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2019 Bielov Andrii
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Stagem
 * @package Stagem_<package>
 * @author Bielov Andrii <bielovandrii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcEntity\GraphQL\Query;

use GraphQL\Doctrine\Types;
use GraphQL\Type\Definition\Type;
use Popov\ZfcEntity\Model\Entity;

class EntitiesQuery
{
    public function __invoke(Types $types)
    {
        return [
            'entities' => [
                'type' => Type::listOf($types->getOutput(Entity::class)), // Use automated ObjectType for output
                'args' => [
                    [
                        'name' => 'filter',
                        'type' => $types->getFilter(Entity::class), // Use automated filtering options
                    ],
                    [
                        'name' => 'sorting',
                        'type' => $types->getSorting(Entity::class), // Use automated sorting options
                    ],
                ],
                'resolve' => function ($root, $args) use ($types) {
                    $queryBuilder = $types->createFilteredQueryBuilder(Entity::class, $args['filter'] ?? [],
                        $args['sorting'] ?? []);
                    $result = $queryBuilder->getQuery()->getResult();

                    return $result;
                },
            ],
        ];
    }
}