<?php

namespace Utils\StaticAnalyse;

use App\Contracts\TestInterface;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Type\IntegerType;

/**
 * @implements Collector<Node\Stmt\Class_, array{string, int}>
 */
class TestCollector implements Collector
{
    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    public function processNode(Node $node, Scope $scope)
    {
        return $this->check($node, $scope);
    }

    private function check(Node $node, Scope $scope)
    {
        return [
            $scope->getClassReflection()?->getName()
        ];

        if (!($node instanceof Node\Stmt\Class_)) {
            return null;
        }

        $className = $scope->getClassReflection();
        if (!$className?->implementsInterface(TestInterface::class)) {
            return null;
        }

        foreach ($node->stmts as $stmt) {
            if (!($stmt instanceof Node\Stmt\Property && $stmt->props[0]->name->toString() === 'test')) {
                continue;
            }
            $propertyValue = $stmt->props[0]->default;
            if (!($propertyValue instanceof Node\Scalar\LNumber)) {
                return ['Class implementing TestInterface must have a numeric $test property'];
            }
            if ($propertyValue->value !== 3) {
                return ['Class implementing TestInterface must have a $test property with value 3'];
            }
            return null;
        }
        return ['Class implementing TestInterface must have a $test property with value 3'];
    }
}
