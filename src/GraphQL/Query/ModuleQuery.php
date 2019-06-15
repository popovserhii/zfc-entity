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

class ModuleQuery
{
    public function __invoke(Types $types)
    {
        return [
            'module' => [
                'type' => $types->getOutput(Module::class), // Use automated ObjectType for output
                'args' => [
                    'id' => Type::nonNull(Type::id()),
                ],
                'resolve' => function ($root, $args, $context) {
                    $result = $context->entityManager->find(Module::class, $args['id']);

                    return $result;
                },
            ],
        ];
    }
}