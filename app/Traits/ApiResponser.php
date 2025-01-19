<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll($data, $code = 200)
    {
        if ($data instanceof LengthAwarePaginator) {
            $data = $data->toArray();
            return $this->successResponse([
                'data' => $data['data'],
                'meta' => [
                    'current_page' => $data['current_page'],
                    'from' => $data['from'],
                    'last_page' => $data['last_page'],
                    'path' => $data['path'],
                    'per_page' => $data['per_page'],
                    'to' => $data['to'],
                    'total' => $data['total'],
                ],
            ], $code);
        }
    
        if ($data instanceof Collection) {
            return $this->successResponse(['data' => $data], $code);
        }
    
        throw new \InvalidArgumentException('Data passed to showAll must be a Collection or LengthAwarePaginator');
    }
    
    
    protected function showOne(Model $model, $code = 200)
    {
        return $this->successResponse(['data' => $model], $code);
    }
    
    protected function filterData(Collection $collection)
    {
        foreach (request()->query() as $query => $value) {
            $collection = $collection->where($query, $value);
        }
    
        return $collection;
    }
    
    protected function paginate(Collection $collection)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
    
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    
        $paginated->appends(request()->all());
    
        return $paginated;
    }
}
