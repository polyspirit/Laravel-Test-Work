<?php
namespace App\Traits;

use Illuminate\Http\Request;

Trait Findable
{
    /**
     * Return array for DB::table('table')->select() statement
     */
    protected function getFields(Request $request): array
    {
        return json_decode($request->input('fields')) ?? ['*'];
    }

    /**
     * Return array for DB::table('table')->where() statement
     */
    protected function getFilter(Request $request): array
    {
        $filter = [];
        if($request->has('filter')) {
            $filterJSON = json_decode($request->input('filter'));

            foreach ($filterJSON as $statement) {
                $filter[] = [$statement->field, $statement->operator, $statement->value];
            }
        }

        return $filter;
    }

    /**
     * Return array for DB::table('table')->orderBy() statement
     */
    protected function getSort(Request $request): array
    {
        $sort = [];
        if($request->has('sort')) {
            $sort = json_decode($request->input('sort'), true);
        }

        $sort['field'] = $sort['field'] ?? 'id';
        $sort['direction'] = $sort['direction'] ?? 'asc';

        return $sort;
    }

    /**
     * Return integer for DB::table('table')->limit() statement
     */
    protected function getLimit(Request $request): int
    {
        return $request->input('limit') ?? -1;
    }

    /**
     * Return array of all filtered table entries
     */
    protected function findAllEntries(Request $request, string $modelName): \Illuminate\Database\Eloquent\Collection
    {
        $sort = $this->getSort($request);

        return $modelName::select(...$this->getFields($request))
                            ->where($this->getFilter($request))
                            ->orderBy($sort['field'], $sort['direction'])
                            ->limit($this->getLimit($request))
                            ->get();

    }
}
