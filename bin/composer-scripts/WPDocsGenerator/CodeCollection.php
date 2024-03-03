<?php
/**
 * Collects all the code objects
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitorAbstract;

/**
 * CodeCollection Class
 */
class CodeCollection extends NodeVisitorAbstract
{
	/**
	 * @var DocItem[]
	 */
	public array $objects = [];

	/**
	 * @var string
	 */
	private string $currentNamespace = '';

	/**
	 * @var ?DocItem
	 */
	private ?DocItem $currentClass = null;

	/**
	 * @param Node $node
	 * @return void
	 */
	public function enterNode(Node $node): void
	{
		if ($node instanceof Node\Stmt\Function_) {
			$this->resetCurrentClass();
			$this->collectFunction($node);
		} elseif ($node instanceof Node\Stmt\Class_) {
			$this->resetCurrentClass();
			$this->collectClass($node);
		} elseif ($node instanceof Node\Stmt\ClassConst) {
			$this->collectClassConst($node);
		} elseif ($node instanceof Node\Stmt\Namespace_) {
			$this->resetCurrentClass();
			$this->currentNamespace = $node->name->toString();
		} elseif ($node instanceof Node\Stmt\ClassMethod) {
			$this->collectMethod($node);
		}
	}

	/**
	 * @param Function_ $node
	 * @return void
	 */
	private function collectFunction(Node\Stmt\Function_ $node): void
	{
		$docItem = $this->createDocItem($node);
		$this->updateObject($docItem);
	}

	/**
	 * @param Node\Stmt\Class_ $node
	 * @return void
	 */
	private function collectClass(Node\Stmt\Class_ $node): void
	{
		$docItem = $this->createDocItem($node);
		$this->collectProperties($node, $docItem);

		$this->updateObject($docItem);
		$this->currentClass = $docItem;
	}

	/**
	 * @param Node\Stmt\Class_ $node
	 * @param DocItem $docItem
	 * @return void
	 */
	private function collectProperties(Node\Stmt\Class_ $node, DocItem $docItem): void
	{
		foreach ($node->stmts as $stmt) {
			if ($stmt instanceof Node\Stmt\Property) {
				foreach ($stmt->props as $property) {
					$propertyName = $property->name->toString();

					$type = $stmt->type ? get_class($stmt->type) : $property->getType();

					// Create a new DocItem for each property
					$propertyDocItem = new DocItem($propertyName);
					$propertyDocItem->type = $type;
					$propertyDocItem->namespace = $this->currentNamespace;
					$propertyDocItem->description = $this->getDescription($stmt);
					$propertyDocItem->isNullable = $this->isNullable($stmt->type);
					$propertyDocItem->isStatic = $stmt->isStatic();
					$propertyDocItem->returnTypes = $this->getTypesArray($stmt->type);
					$propertyDocItem->defaultValue = $this->getPropertyDefaultValue($property);

					// Add the property DocItem to the class
					$docItem->addProperty($propertyDocItem);
				}
			}
		}
	}

	/**
	 * @param Node\Stmt\ClassMethod $node
	 * @return void
	 */
	private function collectMethod(Node\Stmt\ClassMethod $node): void
	{
		$docItem = $this->createDocItem($node);
		$this->updateObject($docItem);
	}

	/**
	 * @param Node\Stmt\ClassConst $node
	 * @return void
	 */
	private function collectClassConst(Node\Stmt\ClassConst $node): void
	{
		$constName = $node->consts[0]->name->toString();

		$docItem = new DocItem($constName);
		$docItem->type = get_class($node);
		$docItem->description = $this->getDescription($node);
		$docItem->isStatic = true;
		$docItem->class = $this->currentClass?->name;
		$docItem->namespace = $this->currentNamespace;

		$this->updateObject($docItem);

		if ($this->currentClass) {
			if( array_key_exists( $this->currentClass->getReference(), $this->objects ) ) {
				$this->objects[$this->currentClass->getReference()]->constants[] = $docItem;
			} else {
				$this->currentClass->constants[] = $docItem;
			}
		}
	}

	/**
	 * @param array $parameters
	 * @return array
	 */
	private function collectParameters(array $parameters, DocItem $docItem): void
	{
		foreach ($parameters as $param) {
			$parameterDocItem = new DocItem($param->var->name);
			$parameterDocItem->type = $this->getTypeString($param->type);
			$parameterDocItem->isNullable = $this->isNullable($param->type);
			$parameterDocItem->description = $this->getDescription($param);

			$docItem->addParameter($parameterDocItem);
		}
	}

	/**
	 * @param mixed $expr
	 * @return string|null
	 */
	private function getPropertyDefaultValue(mixed $expr): ?string
	{
		if ($expr instanceof Node\PropertyItem) {
			return $this->getPropertyDefaultValue($expr->default);
		}

		// Get default value for property
		if ($expr instanceof Node\Expr\ConstFetch) {
			return $expr->name->toString();
		} elseif ($expr instanceof Node\Scalar\String_) {
			return $expr->value;
		} elseif ($expr instanceof Node\Scalar\LNumber) {
			return (string) $expr->value;
		} elseif ($expr instanceof Node\Scalar\DNumber) {
			return (string) $expr->value;
		}

		return null;
	}

	/**
	 * @param $type
	 * @return string
	 */
	private function getTypeString($type): string
	{
		if ($type instanceof Node\UnionType) {
			return implode(
				'|',
				array_map(
					function ($subtype) {
						return $this->getTypeString($subtype);
					},
					$type->types
				)
			);
		} elseif ($type instanceof Node\NullableType) {
			return 'null';
		} elseif ($type !== null) {
			return $type->toString();
		} else {
			return 'mixed';
		}
	}

	/**
	 * @param $type
	 * @return string[]
	 */
	private function getTypesArray($type): array
	{
		if ($type instanceof Node\UnionType) {
			return array_map(
				function ($subtype) {
					return $this->getTypeString($subtype);
				},
				$type->types
			);
		} else {
			return [$this->getTypeString($type)];
		}
	}

	/**
	 * @param Node\Stmt\ClassMethod $stmt
	 * @return string
	 */
	private function getAccessLevel(Node\Stmt\ClassMethod $stmt): string
	{
		if ($stmt->isPublic()) {
			return 'public';
		} elseif ($stmt->isProtected()) {
			return 'protected';
		} elseif ($stmt->isPrivate()) {
			return 'private';
		} else {
			return 'unknown';
		}
	}

	/**
	 * @param Node $node
	 * @return DocItem
	 */
	private function createDocItem(Node $node): DocItem
	{
		$name = is_string( $node->name ) ? $node->name : $node->name->toString();
		$description = $this->getDescription($node);

		$docItem = new DocItem($name);
		$docItem->type = get_class($node);
		$docItem->namespace = $this->currentNamespace;
		$docItem->description = $description;

		if( $node instanceof Node\Stmt\Function_ || $node instanceof Node\Stmt\ClassMethod ) {
			$this->collectParameters($node->params, $docItem);
			$docItem->returnTypes = $this->getTypesArray($node->returnType);
			$docItem->isNullable = $this->isNullable($node->returnType);
		}

		if( $node instanceof Node\Stmt\ClassMethod ) {
			$docItem->access = $this->getAccessLevel($node);
			$docItem->isStatic = $node->isStatic();
			$docItem->class = $this->currentClass?->name;
		}

		return $docItem;
	}

	/**
	 * @param DocItem $docItem
	 * @return void
	 */
	private function updateObject(DocItem $docItem): void
	{
		$this->objects[$docItem->getReference()] = $docItem;
	}

	/**
	 * @param $type
	 * @return bool
	 */
	private function isNullable($type): bool
	{
		return $type instanceof Node\NullableType;
	}

	/**
	 * @param Node $node
	 * @return string
	 */
	private function getDescription(Node $node): string
	{
		$comments = $node->getComments();
		$description = '';

		if (!empty($comments)) {
			$comment = $comments[0]->getText();

			// Remove opening comment tokens (/** and /*)
			$comment = preg_replace('/^\/\*\*|\s*\*\/$/', '', $comment);

			// Remove * tokens at the start of each line and lines starting with "@"
			$comment = preg_replace('/^\s*\*\s*|\s*\*\s*@.*$/m', '', $comment);

			// Remove closing comment tokens (*/)
			$comment = preg_replace('/\/\*\*|\*\//', '', $comment);

			// Remove blank lines
			$comment = preg_replace('/^\s*$/m', '', $comment);

			// Trim any remaining whitespace around the text
			$description = trim($comment);

			// Remove lines starting with "@"
			$lines = explode("\n", $description);
			$filteredLines = array_filter($lines, function ($line) {
				return strpos($line, '@') !== 0;
			});

			$description = implode("\n", $filteredLines);
		}

		return $description;
	}

	/**
	 * @return void
	 */
	private function resetCurrentClass(): void
	{
		$this->currentClass = null;
	}
}
