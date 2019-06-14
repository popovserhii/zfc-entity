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

use GraphQL\Doctrine\Types;
use GraphQL\Type\Definition\Type;
use Popov\ZfcEntity\Model\Entity;

class EntityQuery
{
    public function __invoke(Types $types)
    {
        return [
            'entity' => [
                'type' => $types->getOutput(Entity::class), // Use automated ObjectType for output
                'description' => 'Returns product by id (in range of 1-6)',
                'args' => [
                    'id' => Type::nonNull(Type::id()),
                ],
                'resolve' => function ($root, $args) use ($types) {
                    $queryBuilder = $types->createFilteredQueryBuilder(Entity::class, $args['filter'] ?? [],
                        $args['sorting'] ?? []);
                    $result = $queryBuilder->getQuery()->getArrayResult();

                    return $result;
                },
            ],
        ];
    }
}