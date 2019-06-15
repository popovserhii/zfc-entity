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
use Popov\ZfcEntity\Model\Module;

class ModulesQuery
{
    public function __invoke(Types $types)
    {
        return [
            'modules' => [
                'type' => Type::listOf($types->getOutput(Module::class)), // Use automated ObjectType for output
                'args' => [
                    [
                        'name' => 'filter',
                        'type' => $types->getFilter(Module::class), // Use automated filtering options
                    ],
                    [
                        'name' => 'sorting',
                        'type' => $types->getSorting(Module::class), // Use automated sorting options
                    ],
                ],
                'resolve' => function ($root, $args) use ($types) {
                    $queryBuilder = $types->createFilteredQueryBuilder(Module::class, $args['filter'] ?? [],
                        $args['sorting'] ?? []);
                    $result = $queryBuilder->getQuery()->getArrayResult();

                    return $result;
                },
            ],
        ];
    }
}