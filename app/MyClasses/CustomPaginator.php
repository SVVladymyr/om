<?php 

namespace App\MyClasses;

use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginator
{
	public static function paginate($collection, $per_page = 10)
	{
		$currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->all();

        $entries = new LengthAwarePaginator($currentPageSearchResults, count($collection), $per_page);

        return $entries;
	}
}