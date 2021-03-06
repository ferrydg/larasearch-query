<?php

class AbstractQueryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function it_should_parse_search_query_string_without_negation()
	{
		$test = 'ivo,ferry';
		$expected =  [
			'_all' => [
				[
					'+' =>[
						'ivo',
						'ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_parse_search_query_string_with_negation()
	{
		$test = 'ivo,-ferry';
		$expected =  [
			'_all' => [
				[
					'+' =>[
						'ivo'
					],
					'-' =>[
						'ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_parse_search_query_string_with_escaped_delimiter()
	{
		$test = 'ivo\,ferry';
		$expected =  [
			'_all' => [
					[
					'+' =>[
						'ivo,ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_parse_search_query_string_with_escaped_and_unescaped_delimiter()
	{
		$test = 'ivo,ferry\,lennert';
		$expected =  [
			'_all' => [
				[
					'+' =>[
						'ivo',
						'ferry,lennert'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_parse_search_query_string_with_escaped_negation()
	{
		$test = 'ivo,\-ferry';
		$expected =  [
			'_all' => [
				[
					'+' =>[
						'ivo',
						'-ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_parse_search_query_string_with_escaped_negation_and_slash_dash()
	{
		$test = 'ivo,\-fe\-rry';
		$expected =  [
			'_all' => [
				[
					'+' =>[
						'ivo',
						'-fe\-rry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_parse_search_query_string_with_negation_followed_by_dash()
	{
		$test = 'ivo,--ferry';
		$expected =  [
			'_all' => [
				[
					'+' =>[
						'ivo'
					],
					'-' =>[
						'-ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 * @expectedException Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
	 */
	public function it_should_fail_on_empty_value_part()
	{
		$test = 'ivo,,ferry';

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
	}

	/**
	 * @test
	 * @expectedException Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
	 */
	public function it_should_fail_on_negated_empty_value_part()
	{
		$test = 'ivo,-,ferry';

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
	}

	/**
	 * @test
	 */
	public function it_should_split_on_pipe_delimiter()
	{
		$test = 'ivo|ferry';
		$expected = [
			'_all' => [
				[
					'+' => [
						'ivo'
					]
				],
				[
					'+' => [
						'ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_split_on_pipe_delimiter_and_negated_value()
	{
		$test = 'ivo|-ferry';
		$expected = [
			'_all' => [
				[
					'+' => [
						'ivo'
					]
				],
				[
					'-' => [
						'ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_not_split_on_escaped_pipe_delimiter()
	{
		$test = 'ivo\|ferry';
		$expected = [
			'_all' => [
				[
					'+' => [
						'ivo|ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_not_split_on_start_with_escaped_pipe_delimiter()
	{
		$test = '\|ferry';
		$expected = [
			'_all' => [
				[
					'+' => [
						'|ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 * @expectedException Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
	 */
	public function it_should_fail_on_empty_query_part()
	{
		$test = 'ivo||ferry';

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
	}

	/**
	 * @test
	 * @expectedException Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
	 */
	public function it_should_fail_on_negated_empty_query_part()
	{
		$test = 'ivo|-|ferry';

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
	}

	/**
	 * @test
	 */
	public function it_should_split_on_double_colon()
	{
		$test = 'ivo::ferry';
		$expected = [
			'ivo' => [
				[
					'+' => [
						'ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 */
	public function it_should_not_split_on_escaped_double_colon()
	{
		$test = 'ivo\::ferry';
		$expected = [
			'_all' => [
				[
					'+' => [
						'ivo::ferry'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}

	/**
	 * @test
	 * @expectedException Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
	 */
	public function it_should_fail_on_empty_value_part_with_fieldname()
	{
		$test = 'ivo::';

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
	}

	/**
	 * @test
	 * @expectedException Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
	 */
	public function it_should_fail_on_negated_empty_value_part_with_fieldname()
	{
		$test = 'ivo::-';

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
	}

	/**
	 * @test
	 */
	public function it_should_split_on_pipe_and_double_colon()
	{
		$test = 'ivo::ferry|lennert';
		$expected = [
			'ivo' => [
				[
					'+' => [
						'ferry'
					]
				]
			],
			'_all' => [
				[
					'+' => [
						'lennert'
					]
				]
			]
		];

		$query = new \Eur\Ods\Support\Query\ElasticsearchQuery('');
		$query->setQuery($test);
		$this->assertEquals($expected, $query->getQuery());
	}
} 