<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

Trait ApiResponser
{
    protected $errorMessage = '';
    protected $errorCode = 400;
    protected $errorData = null;

    /**
     * Return a success JSON response.
     */
    protected function success(mixed $data, int $code = 200): \Illuminate\Http\JsonResponse
    {
        return $this->jsonResponse('success', $data, '', $code);
    }

    /**
     * Return an error JSON response.
     */
	protected function error(string $message = '', int $code = 0): \Illuminate\Http\JsonResponse
	{
        $message = $message ?: $this->errorMessage ?: 'Unknown error';
        $code = $code ?: $this->errorCode ?: 400;

        return $this->jsonResponse('error', $this->errorData, $message, $code);
	}

    /**
     * Return a JSON response.
     */
    private function jsonResponse(string $status, mixed $data, string $message, int $code): \Illuminate\Http\JsonResponse
    {
        return response()->json([
			'status' => $status,
			'message' => $message,
			'data' => $data
		], $code);
    }

    protected function validateRequest(?Request $request = null, array $validationRules = []): bool
    {
        if($request && $validationRules) {
            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                $this->errorMessage = 'Validation error';
                $this->errorCode = 406;
                $this->errorData = $validator->errors();

                return false;
            }
        }

        return true;
    }
}
