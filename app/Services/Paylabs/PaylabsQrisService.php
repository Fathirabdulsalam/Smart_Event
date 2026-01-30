<?php

namespace App\Services\Paylabs;

use App\Models\Payment;
use Illuminate\Support\Arr;

class PaylabsQrisService
{
    public function __construct(
        private readonly PaylabsHttpClient $client = new PaylabsHttpClient(),
    ) {
    }

    public function create(array $input): array
    {
        $merchantId = \config('paylabs.merchant_id');
        $requestId = $input['requestId'] ?? $this->client->requestId();
        $merchantTradeNo = $input['merchantTradeNo'] ?? $this->client->merchantTradeNo();

        $payload = [
            'merchantId' => $merchantId,
            'requestId' => $requestId,
            'merchantTradeNo' => $merchantTradeNo,
            'paymentType' => 'QRIS',
            'amount' => number_format((float) $input['amount'], 2, '.', ''),
            'productName' => (string) $input['productName'],
        ];

        if (!empty($input['storeId'])) {
            $payload['storeId'] = (string) $input['storeId'];
        }
        $notifyUrl = !empty($input['notifyUrl']) ? (string) $input['notifyUrl'] : (string) \config('paylabs.notify_url');
        if (trim($notifyUrl) !== '') {
            $payload['notifyUrl'] = $notifyUrl;
        }
        if (!empty($input['feeType'])) {
            $payload['feeType'] = (string) $input['feeType'];
        }
        if (!empty($input['productInfo']) && is_array($input['productInfo'])) {
            $payload['productInfo'] = $input['productInfo'];
        }

        $payment = Payment::create([
            'provider' => 'paylabs',
            'payment_type' => 'QRIS',
            'merchant_id' => $merchantId,
            'request_id' => $requestId,
            'merchant_trade_no' => $merchantTradeNo,
            'amount' => $payload['amount'],
            'product_name' => $payload['productName'],
            'status' => null,
            'raw_request' => $payload,
        ]);

        $result = $this->client->post('/qris/create', $payload, $requestId);

        $json = is_array($result['json']) ? $result['json'] : [];

        $payment->update([
            'platform_trade_no' => Arr::get($json, 'platformTradeNo'),
            'status' => Arr::get($json, 'status'),
            'err_code' => Arr::get($json, 'errCode'),
            'err_code_des' => Arr::get($json, 'errCodeDes'),
            'qr_code' => Arr::get($json, 'qrCode'),
            'qris_url' => Arr::get($json, 'qrisUrl'),
            'nmid' => Arr::get($json, 'nmid'),
            'rrn' => Arr::get($json, 'rrn'),
            'tid' => Arr::get($json, 'tid'),
            'payer' => Arr::get($json, 'payer'),
            'phone_number' => Arr::get($json, 'phoneNumber'),
            'issuer_id' => Arr::get($json, 'issuerId'),
            'expired_time' => Arr::get($json, 'expiredTime'),
            'raw_response' => $json,
        ]);

        return [
            'payment' => $payment,
            'paylabs' => $result,
        ];
    }

    public function query(array $input): array
    {
        $merchantId = \config('paylabs.merchant_id');

        $payload = [
            'merchantId' => $merchantId,
            'requestId' => (string) $input['requestId'],
            'paymentType' => 'QRIS',
        ];

        if (!empty($input['merchantTradeNo'])) {
            $payload['merchantTradeNo'] = (string) $input['merchantTradeNo'];
        }
        if (!empty($input['rrn'])) {
            $payload['rrn'] = (string) $input['rrn'];
        }
        if (!empty($input['storeId'])) {
            $payload['storeId'] = (string) $input['storeId'];
        }

        $result = $this->client->post('/qris/query', $payload, (string) $input['requestId']);

        $json = is_array($result['json']) ? $result['json'] : [];

        $payment = Payment::query()
            ->where('request_id', $payload['requestId'])
            ->orWhere('merchant_trade_no', Arr::get($payload, 'merchantTradeNo'))
            ->first();

        if ($payment) {
            $payment->update([
                'platform_trade_no' => Arr::get($json, 'platformTradeNo', $payment->platform_trade_no),
                'status' => Arr::get($json, 'status', $payment->status),
                'err_code' => Arr::get($json, 'errCode', $payment->err_code),
                'err_code_des' => Arr::get($json, 'errCodeDes', $payment->err_code_des),
                'qr_code' => Arr::get($json, 'qrCode', $payment->qr_code),
                'qris_url' => Arr::get($json, 'qrisUrl', $payment->qris_url),
                'nmid' => Arr::get($json, 'nmid', $payment->nmid),
                'rrn' => Arr::get($json, 'rrn', $payment->rrn),
                'tid' => Arr::get($json, 'tid', $payment->tid),
                'payer' => Arr::get($json, 'payer', $payment->payer),
                'phone_number' => Arr::get($json, 'phoneNumber', $payment->phone_number),
                'issuer_id' => Arr::get($json, 'issuerId', $payment->issuer_id),
                'expired_time' => Arr::get($json, 'expiredTime', $payment->expired_time),
                'raw_response' => $json,
            ]);

            if (Arr::get($json, 'status') === '02' && !$payment->paid_at) {
                $payment->update(['paid_at' => \now()]);
            }
        }

        return [
            'payment' => $payment,
            'paylabs' => $result,
        ];
    }

    public function handleNotification(array $input): array
    {
        $merchantId = \config('paylabs.merchant_id');

        $path = (string) ($input['path'] ?? '');
        $rawBody = (string) ($input['rawBody'] ?? '');
        $signature = (string) ($input['signature'] ?? '');
        $timestamp = (string) ($input['timestamp'] ?? '');

        $hash = strtolower(hash('sha256', $rawBody));
        $stringToVerify = "POST:{$path}:{$hash}:{$timestamp}";

        $publicKey = PaylabsKeyResolver::resolvePublicKey();

        $verified = false;
        if ($publicKey && $signature && $timestamp && $path) {
            $verified = PaylabsCrypto::verify($stringToVerify, $signature, $publicKey);
        }

        $payload = json_decode($rawBody, true);
        if (!is_array($payload)) {
            $payload = [];
        }

        $payment = Payment::query()
            ->where('request_id', Arr::get($payload, 'requestId'))
            ->orWhere('merchant_trade_no', Arr::get($payload, 'merchantTradeNo'))
            ->first();

        if ($payment) {
            $payment->update([
                'status' => Arr::get($payload, 'status', $payment->status),
                'err_code' => Arr::get($payload, 'errCode', $payment->err_code),
                'err_code_des' => Arr::get($payload, 'errCodeDes', $payment->err_code_des),
                'platform_trade_no' => Arr::get($payload, 'platformTradeNo', $payment->platform_trade_no),
                'qr_code' => Arr::get($payload, 'qrCode', $payment->qr_code),
                'qris_url' => Arr::get($payload, 'qrisUrl', $payment->qris_url),
                'expired_time' => Arr::get($payload, 'expiredTime', $payment->expired_time),
                'nmid' => Arr::get($payload, 'paymentMethodInfo.nmid', $payment->nmid),
                'rrn' => Arr::get($payload, 'paymentMethodInfo.rrn', $payment->rrn),
                'tid' => Arr::get($payload, 'paymentMethodInfo.tid', $payment->tid),
                'payer' => Arr::get($payload, 'paymentMethodInfo.payer', $payment->payer),
                'phone_number' => Arr::get($payload, 'paymentMethodInfo.phoneNumber', $payment->phone_number),
                'issuer_id' => Arr::get($payload, 'paymentMethodInfo.issuerId', $payment->issuer_id),
                'notify_payload' => $payload,
            ]);

            if (Arr::get($payload, 'status') === '02' && !$payment->paid_at) {
                $payment->update(['paid_at' => \now()]);
            }
        }

        return [
            'verified' => $verified,
            'payment' => $payment,
            'payload' => $payload,
            'response' => [
                'merchantId' => $merchantId,
                'requestId' => Arr::get($payload, 'requestId'),
                'errCode' => '0',
                'signatureVerified' => $verified,
            ],
        ];
    }
}
