<?php

namespace App\Http\Controllers;

use App\Services\Paylabs\PaylabsQrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaylabsCallbackController extends Controller
{
    public function __construct(
        private readonly PaylabsQrisService $service = new PaylabsQrisService(),
    ) {
    }

    public function qrisCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1'],
            'productName' => ['required', 'string', 'max:100'],
            'merchantTradeNo' => ['nullable', 'string', 'max:32'],
            'requestId' => ['nullable', 'string', 'max:64'],
            'notifyUrl' => ['nullable', 'url', 'max:200'],
            'storeId' => ['nullable', 'string', 'max:30'],
            'feeType' => ['nullable', 'string', 'in:BEN,OUR'],
            'productInfo' => ['nullable', 'array', 'max:30'],
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->service->create($validator->validated());

        return \response()->json([
            'ok' => true,
            'payment' => $result['payment'],
            'paylabs' => $result['paylabs']['json'],
            'debug' => [
                'request_headers' => $result['paylabs']['headers'],
                'http_status' => $result['paylabs']['status'],
            ],
        ], 200);
    }

    public function qrisQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requestId' => ['required', 'string', 'max:64'],
            'merchantTradeNo' => ['nullable', 'string', 'max:32'],
            'rrn' => ['nullable', 'string', 'max:32'],
            'storeId' => ['nullable', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if (empty($data['merchantTradeNo']) && empty($data['rrn'])) {
            return \response()->json([
                'ok' => false,
                'message' => 'merchantTradeNo atau rrn wajib diisi salah satu',
            ], 422);
        }

        $result = $this->service->query($data);

        return \response()->json([
            'ok' => true,
            'payment' => $result['payment'],
            'paylabs' => $result['paylabs']['json'],
            'debug' => [
                'request_headers' => $result['paylabs']['headers'],
                'http_status' => $result['paylabs']['status'],
            ],
        ], 200);
    }

    public function qrisNotify(Request $request)
    {
        $result = $this->service->handleNotification([
            'path' => $request->getPathInfo(),
            'rawBody' => $request->getContent(),
            'signature' => (string) $request->header('X-SIGNATURE', ''),
            'timestamp' => (string) $request->header('X-TIMESTAMP', ''),
        ]);

        return \response()->json($result['response'], 200);
    }

    public function handle(Request $request)
    {
        return $this->qrisNotify($request);
    }
}
