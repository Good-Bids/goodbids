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
	public string $type;

	/**
	 * @var string
	 */
	public string $name;

	/**
	 * @var ?string
	 */
	public ?string $namespace = null;

	/**
	 * @var ?string
	 */
	public ?string $class = null;

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
	public mixed $defaultValue;

	/**
	 * DocItem constructor.
	 *
	 * @param string $name
	 */
	public function __construct( string $name ) {
		$this->name = $name;
	}

	/**
	 * @param DocItem $propertyDocItem
	 * @return void
	 */
	public function addProperty(DocItem $propertyDocItem): void
	{
		$this->properties[] = $propertyDocItem;
	}

	/**
	 * @param DocItem $parameterDocItem
	 * @return void
	 */
	public function addParameter(DocItem $parameterDocItem): void
	{
		$this->parameters[] = $parameterDocItem;
	}

	/**
	 * @return string
	 */
	public function getReference(): string
	{
		$reference = $this->name;

		if ( $this->class ) {
			$reference = $this->class . '::' . $reference;
		}

		if ( $this->namespace ) {
			$reference = $this->namespace . '\\' . $reference;
		}

		return $reference;
	}
}
