<?php
/**
 * DocItem
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator;

/**
 * DocItem Class
 */
class DocItem {

	/**
	 * @var string
	 */
	public string $name;

	/**
	 * @var ?string
	 */
	public ?string $path = null;

	/**
	 * @var int
	 */
	public int $lineNumber;

	/**
	 * @var ?string
	 */
	public ?string $node = null;

	/**
	 * @var ?string
	 */
	public ?string $namespace = null;

	/**
	 * @var ?string
	 */
	public ?string $class = null;

	/**
	 * @var ?string
	 */
	public ?string $function = null;

	/**
	 * @var array
	 */
	public array $constants = [];

	/**
	 * @var array
	 */
	public array $properties = [];

	/**
	 * @var array
	 */
	public array $methods = [];

	/**
	 * @var ?string
	 */
	public ?string $access = null;

	/**
	 * @var bool
	 */
	public bool $isStatic = false;

	/**
	 * @var bool
	 */
	public bool $isNullable = false;

	/**
	 * @var array
	 */
	public array $returnTypes = [];

	/**
	 * @var array
	 */
	public array $parameters = [];

	/**
	 * @var string
	 */
	public string $description = '';

	/**
	 * @var mixed
	 */
	public mixed $defaultValue = null;

	/**
	 * DocItem constructor.
	 *
	 * @param string $name
	 * @param int $lineNumber
	 */
	public function __construct( string $name, int $lineNumber ) {
		$this->name = $name;
		$this->lineNumber = $lineNumber;
	}

	/**
	 * @param DocItem $constantDocItem
	 * @return void
	 */
	public function addConstant(DocItem $constantDocItem): void
	{
		$this->constants[ $constantDocItem->getReference() ] = $constantDocItem;
	}

	/**
	 * @param DocItem $methodDocItem
	 * @return void
	 */
	public function addMethod(DocItem $methodDocItem): void
	{
		$this->methods[ $methodDocItem->getReference() ] = $methodDocItem;
	}

	/**
	 * @param DocItem $propertyDocItem
	 * @return void
	 */
	public function addProperty(DocItem $propertyDocItem): void
	{
		$this->properties[ $propertyDocItem->getReference() ] = $propertyDocItem;
	}

	/**
	 * @param DocItem $parameterDocItem
	 * @return void
	 */
	public function addParameter(DocItem $parameterDocItem): void
	{
		$this->parameters[ $parameterDocItem->getReference() ] = $parameterDocItem;
	}

	/**
	 * @return string
	 */
	public function getReference( bool $unique = false ): string
	{
		$reference = $this->name;

		if ( 'constant' === $this->node ) {
			return $reference;
		}

		if ( in_array( $this->node, [ 'parameter', 'property' ], true ) ) {
			$reference = '$' . $reference;

			if ( ! $unique ) {
				return $reference;
			}
		}

		if ( $this->class ) {
			$separator = $this->isStatic ? '::' : '->';
			$reference = $this->class . $separator . $reference;
		}

		if ( $this->namespace ) {
			$reference = $this->namespace . '\\' . $reference;
		}

		return $reference;
	}
}
