<?php declare(strict_types = 1);

/**
 * This file is part of the Nextras\Orm library.
 * @license    MIT
 * @link       https://github.com/nextras/orm
 */

namespace Nextras\Orm\Mapper\Dbal\CustomFunctions;

use Nextras\Dbal\QueryBuilder\QueryBuilder;
use Nextras\Orm\Mapper\Dbal\QueryBuilderHelper;


interface IQueryBuilderFilterFunction
{
	/**
	 * @param array<mixed> $args
	 * @return array<mixed> list of Nextras Dbal's condition fragments
	 */
	public function processQueryBuilderFilter(QueryBuilderHelper $helper, QueryBuilder $builder, array $args): array;
}
