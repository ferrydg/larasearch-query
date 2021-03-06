<?php namespace Iverberk\LarasearchQuery;

use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

abstract class AbstractQuery {

	/**
	 * @var string
	 */
	protected $query;

	/**
	 * @var string
	 */
	protected $class;

	/**
	 * @param string $query
	 * @param $class
	 */
	function __construct($class, $query = null)
	{
		$this->class = $class;
		$this->query = $query;
	}

	/**
	 * @return mixed
	 */
	abstract public function generate();

	/**
	 * @return array
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @param string $query
	 */
	public function setQuery($query)
	{
		$this->query = $this->parseQuery($query);
	}

	/**
	 * @param $query
	 * @return array
	 */
	protected function parseQuery($queryString)
	{
		$query = [];

		// Split on |
		$parts = $this->splitString($queryString, '|');

		foreach($parts as $part)
		{
			$queryPart = [];

			// Determine field
			$fieldValue = $this->splitString($part, '::', 2);

			$field = count($fieldValue) == 2 ? $fieldValue[0] : '_all';
			$valueString = count($fieldValue) == 2 ? $fieldValue[1] : $fieldValue[0];

			// Split on ,
			$values = $this->splitString($valueString, ',');
			foreach ($values as $value)
			{
				if (empty($value) || '-' == $value)
				{
					throw new NotAcceptableHttpException('Empty query part found');
				}

				if (strpos($value, '-') === 0)
				{
					$queryPart['-'][] = substr($value, 1);
				} else
				{
					$queryPart['+'][] = preg_replace('/^\\\-/', '-', $value);
				}
			}

			$query[$field][] = $queryPart;
		}

		return $query;
	}

	protected function splitString($string, $delimiter = ',', $limit = -1)
	{
		$parts = preg_split('~(?<!\\\)' . preg_quote($delimiter, '~') . '~', $string, $limit);

		return str_replace('\\' . $delimiter, $delimiter, $parts);
	}
}