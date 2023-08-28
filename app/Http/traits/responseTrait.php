<?php
namespace App\Http\traits;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response as ResponseAlias;

trait responseTrait{
    /**
     * Building success response
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($response = null)
    {
        if ($response instanceof LengthAwarePaginator) {
            return response()->json([$response], ResponseAlias::HTTP_OK);
        }
        if ($response) {
            return response()->json(['data' => $response], ResponseAlias::HTTP_OK);
        }

        return response()->json(['data' => [
            'success' => true
        ]], ResponseAlias::HTTP_OK);
    }

    /**
     * Building success response
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($exception=null, $statusCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,$message=null)
    {
        if ($exception==null && $message!=null)
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => $message
                ]
            ], $statusCode);
        return response()->json([
            'data' => [
                'success' => false,
                'message' => $exception->getMessage()
            ]
        ], $statusCode);
    }
}
