<?php

namespace Utils\StaticAnalyse\Rule;

use App\Contracts\TestInterface;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;

/**
 * @implements \PHPStan\Rules\Rule<Node\Expr\New_>
 */
class MyRule implements \PHPStan\Rules\Rule
{
    public function getNodeType(): string
    {
        return ClassPropertyNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node instanceof ClassPropertyNode) {
            if ($node->getClassReflection()?->implementsInterface(TestInterface::class)
                && $node->getDefault()->value === 3
            ) {
                return ['Incorrect property $test'];
            }
        }
        return [];
    }
}
