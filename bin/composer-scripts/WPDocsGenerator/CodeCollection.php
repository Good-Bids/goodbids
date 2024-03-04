<?php
/**
 * Collects all the code objects
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
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
	 * @var DocItem[]
	 */
	public array $tree = [];

	/**
	 * @var string
	 */
	private string $currentNamespace = '';

	/**
	 * @var ?DocItem
	 */
	private ?DocItem $currentClass = null;

	/**
	 * @var ?DocItem
	 */
	private ?DocItem $currentFunction = null;

	/**
	 * @var string
	 */
	public string $path = '';

	/**
	 * @param string $path
	 */
	public function __construct( string $path )
	{
		$this->path = $path;
	}

	/**
	 * @param Node $node
	 * @return void
	 */
	public function enterNode(Node $node): void
	{
		if ($node instanceof Node\Stmt\Namespace_) {
			$this->resetCurrentClass();
			$this->resetCurrentFunction();
			$this->currentNamespace = $node->name->toString();
		} elseif ($node instanceof Node\Stmt\Class_) {
			$this->resetCurrentClass();
			$this->resetCurrentFunction();
			$this->collectClass($node);
		} elseif ($node instanceof Function_) {
			$this->resetCurrentClass();
			$this->resetCurrentFunction();
			$this->collectFunction($node);
		} elseif ($node instanceof Node\Stmt\Const_) {
			$this->resetCurrentFunction();
			$this->collectConst($node);
		}
	}

	/**
	 * @param Function_ $node
	 * @return void
	 */
	private function collectFunction(Function_ $node): void
	{
		$functionDocItem = $this->createDocItem($node);
		$this->addObject($functionDocItem);
	}

	/**
	 * @param Node\Stmt\Class_ $node
	 * @return void
	 */
	private function collectClass(Node\Stmt\Class_ $node): void
	{
		$classDocItem = $this->createDocItem($node);
		$this->currentClass = $classDocItem;
		$this->collectProperties($node);
		$this->collectClassConstants($node);
		$this->collectClassMethods($node);

		$this->addObject($classDocItem);
	}

	/**
	 * @param Node\Stmt\Class_ $node
	 * @return void
	 */
	private function collectProperties(Node\Stmt\Class_ $node): void
	{
		foreach ($node->stmts as $stmt) {
			if ($stmt instanceof Node\Stmt\Property) {
				foreach ($stmt->props as $property) {
					$propertyName = $property->name->toString();

					$propertyDocItem = $this->createDocItem($stmt, $propertyName, $property->getStartLine());
					$propertyDocItem->isNullable = $this->isNullable($stmt->type);
					$propertyDocItem->access = $this->getPropertyAccessLevel($stmt);
					$propertyDocItem->isStatic = $stmt->isStatic();
					$propertyDocItem->returnTypes = $this->getTypesArray($stmt->type);
					$propertyDocItem->defaultValue = $this->getPropertyDefaultValue($property);

					$this->currentClass->addProperty($propertyDocItem);
					$this->addObject($propertyDocItem, false);
				}
			}
		}
	}

	/**
	 * @param Node\Stmt\Class_ $node
	 * @return void
	 */
	private function collectClassConstants(Node\Stmt\Class_ $node): void
	{
		foreach ($node->stmts as $stmt) {
			if ($stmt instanceof Node\Stmt\ClassConst) {
				foreach ($stmt->consts as $constant) {
					$constName = $constant->name->toString();

					$constDocItem = $this->createDocItem($constant, $constName);
					$constDocItem->node = 'class-constant';
					$constDocItem->isStatic = true;

					$this->currentClass->addConstant($constDocItem);
					$this->addObject($constDocItem, false);
				}
			}
		}
	}

	/**
	 * @param Node\Stmt\Class_ $node
	 * @return void
	 */
	private function collectClassMethods(Node\Stmt\Class_ $node): void
	{
		foreach ($node->stmts as $stmt) {
			if ($stmt instanceof Node\Stmt\ClassMethod) {
				$methodDocItem = $this->createDocItem($stmt);
				$this->currentClass->addMethod($methodDocItem);
				$this->addObject($methodDocItem, false);
			}
		}
	}

	/**
	 * Collect Constants
	 * @param Node\Stmt\Const_ $node
	 * @return void
	 */
	private function collectConst(Node\Stmt\Const_ $node): void
	{
		foreach ($node->consts as $const) {
			$constDocItem = $this->createDocItem( $const );
			$constDocItem->isStatic = true; // Constants are always static
			$this->addObject($constDocItem);
		}
	}

	/**
	 * @param Node\Param[] $parameters
	 * @return void
	 */
	private function collectParameters(array $parameters): void
	{
		foreach ($parameters as $param) {
			$parameterDocItem = $this->createDocItem($param, $param->var->name);
			$this->currentFunction->addParameter($parameterDocItem);
			$this->addObject($parameterDocItem, false);
		}
	}

	/**
	 * @param mixed $expr
	 * @return ?string
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
			return 'null|' . $this->getTypeString($type->type);
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
		} elseif ($type instanceof Node\NullableType) {
			return explode('|', $this->getTypeString($type));
		} elseif ($type !== null) {
			return [$this->getTypeString($type)];
		} else {
			return ['mixed'];
		}
	}

	/**
	 * @param ClassMethod $stmt
	 * @return string
	 */
	private function getAccessLevel(ClassMethod $stmt): string
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
	 * @param Node\Stmt\Property $stmt
	 * @return string
	 */
	private function getPropertyAccessLevel(Node\Stmt\Property $stmt): string
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
	 * @param ?string $name
	 * @param ?int $startLine
	 * @return DocItem
	 */
	private function createDocItem(Node $node, string $name = null, int $startLine = null): DocItem
	{
		if ( is_null( $name ) ) {
			$name = is_string( $node->name ) ? $node->name : $node->name->toString();
		}
		if ( is_null( $startLine ) ) {
			$startLine = $node->getStartLine();
		}

		$docItem = new DocItem($name, $startLine);
		$docItem->path = $this->path;
		$docItem->node = $this->getNodeType($node);
		$docItem->description = $this->getDescription($node);

		// Constants are in the Global Namespace.
		if ( ! $node instanceof Node\Stmt\Const_ ) {
			$docItem->namespace = $this->currentNamespace;

			if ($this->currentClass) {
				$docItem->class = $this->currentClass->name;
			}

			if ($this->currentFunction) {
				$docItem->function = $this->currentFunction->name;
			}
		}

		if( $node instanceof Function_ || $node instanceof ClassMethod ) {
			$this->currentFunction = $docItem;
			$this->collectParameters($node->params);
		}

		if( $node instanceof ClassMethod ) {
			$docItem->access = $this->getAccessLevel($node);
			$docItem->isStatic = $node->isStatic();
		}

		if( $node instanceof Function_ || $node instanceof  ClassMethod ) {
			$docItem->returnTypes = $this->getTypesArray($node->returnType);
			$docItem->isNullable = $this->isNullable($node->returnType);
		}

		if( $node instanceof Node\Param || $node instanceof Node\Stmt\Property ) {
			$docItem->returnTypes = [$this->getTypeString($node->type)];
			$docItem->isNullable = $this->isNullable($node->type);
		}

		return $docItem;
	}

	/**
	 * Get the node type
	 * @param $node
	 * @return string
	 */
	private function getNodeType($node): string
	{
		$class = get_class( $node );

		return match( $class ) {
			Node\Stmt\Namespace_::class => 'namespace',
			Node\Stmt\Const_::class => 'constant',
			Node\Stmt\Class_::class => 'class',
			Function_::class => 'function',
			ClassMethod::class => 'method',
			Node\Stmt\ClassConst::class => 'class-constant',
			Node\Stmt\Property::class => 'property',
			Node\Param::class => 'parameter',
			default => $class,
		};
	}

	/**
	 * @param DocItem $docItem
	 * @param bool $addToTree
	 * @return void
	 */
	private function addObject(DocItem $docItem, bool $addToTree = true): void
	{
		$this->objects[$docItem->getReference()] = $docItem;

		if ( $addToTree ) {
			$this->tree[$docItem->getReference()] = $docItem;
		}
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

	/**
	 * @return void
	 */
	private function resetCurrentFunction(): void
	{
		$this->currentFunction = null;
	}
}
