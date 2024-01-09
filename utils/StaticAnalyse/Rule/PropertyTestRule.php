<?php

namespace Utils\StaticAnalyse\Rule;

use App\Contracts\TestInterface;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use Utils\StaticAnalyse\TestCollector;

class PropertyTestRule implements Rule
{
    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        return [];

        //var_dump(get_class($node));
        //return [];

        $traitDeclarationData = $node->get(TestCollector::class);
        //var_dump($traitDeclarationData);

        // $traitDeclarationData is array<string, list<array{string, int}>>
        foreach ($traitDeclarationData as $file => $declarations) {
            foreach ($declarations as $item) {
                var_dump($item);
            }
        }
        return [];

        if ($node instanceof \PHPStan\Node\CollectedDataNode) {
            $items = $node->get(Node\Stmt\Class_::class);
            foreach ($items as $item) {
                return $this->check($item, $scope);
            }
        } else {
            return $this->check($node, $scope);
        }
        return [];
    }

    private function check(Node $node, Scope $scope)
    {
        if (!($node instanceof Node\Stmt\Class_)) {
            return [];
        }

        $className = $scope->getClassReflection();
        if (!$className?->implementsInterface(TestInterface::class)) {
            return [];
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
            return [];
        }
        return ['Class implementing TestInterface must have a $test property with value 3'];
    }
}
