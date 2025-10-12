<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessConfiguration;
class FbrInvoiceService
{
    protected string $env; // sandbox or production
    protected string $token;
    protected string $baseUrl;
    public function __construct(string $env = 'sandbox')
    {
        $this->env = $env;
        $this->baseUrl = 'https://gw.fbr.gov.pk/di_data/v1/di/';
        $this->setToken();
    }
    public function validateInvoice(array $payload): array
    {
        try {
            $url = $this->baseUrl . ($this->env === 'production' ? 'validateinvoicedata' : 'validateinvoicedata_sb');
            $jsonData = json_encode($payload);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->token,
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response, true);
            $dated = $data['dated'] ?? null;
            $invoiceStatuses = $data['validationResponse']['invoiceStatuses'] ?? [];
            $firstInvoiceStatus = $invoiceStatuses[0] ?? [];
            $error = !empty($data['validationResponse']['error'])
                ? $data['validationResponse']['error']
                : ($firstInvoiceStatus['error'] ?? 'Unknown error');
            $errorCode = !empty($data['validationResponse']['errorCode'])
                ? $data['validationResponse']['errorCode']
                : ($firstInvoiceStatus['errorCode'] ?? null);
            $status = $data['validationResponse']['status'] ?? null;
            $statusCode = $data['validationResponse']['statusCode'] ?? null;
            return [
                'success'         => $statusCode === '00' && $status === 'Valid',
                'dated'           => $dated,
                'data'            => $data,
                'statusCode'      => $statusCode,
                'status'          => $status,
                'errorCode'       => $errorCode,
                'error'           => $error,
                'invoiceStatuses' => $invoiceStatuses,
            ];
        } catch (\Exception $e) {
            Log::error('Invoice Posting Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    public function postInvoice(array $payload): array
    {
        try {
            $url = $this->baseUrl . ($this->env === 'production' ? 'postinvoicedata' : 'postinvoicedata_sb');
            $jsonData = json_encode($payload);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->token,
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response, true);
            // Extract fields safely
            $dated         = $data['dated'] ?? null;
            $invoiceNumber = $data['invoiceNumber'] ?? null;
            $validationResponse = $data['validationResponse'] ?? [];
            $invoiceStatuses    = $validationResponse['invoiceStatuses'] ?? [];
            $firstInvoiceStatus = $invoiceStatuses[0] ?? [];
            // Error fallback handling
            $error = !empty($validationResponse['error'])
                ? $validationResponse['error']
                : ($firstInvoiceStatus['error'] ?? 'Unknown error');
            $errorCode = !empty($validationResponse['errorCode'])
                ? $validationResponse['errorCode']
                : ($firstInvoiceStatus['errorCode'] ?? null);
            $status     = $validationResponse['status'] ?? null;
            $statusCode = $validationResponse['statusCode'] ?? null;
            return [
                'success'        => $statusCode === '00'
                    && $status === 'Valid'
                    && !empty($invoiceNumber),
                'dated'          => $dated,
                'data'           => $data,
                'statusCode'     => $statusCode,
                'status'         => $status,
                'errorCode'      => $errorCode,
                'error'          => $error,
                'invoiceStatuses' => $invoiceStatuses,
            ];
        } catch (\Exception $e) {
            Log::error('Invoice Posting Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ];
    }
    protected function setToken()
    {
        $tenantId = Auth::user()->tenant_id;
        $config = BusinessConfiguration::where('bus_config_id', $tenantId)->first();
        if (!$config) {
            throw new \Exception("Business configuration not found for tenant ID {$tenantId}");
        }
        $this->token = $config->fbr_env === 'production'
            ? $config->fbr_api_token_prod
            : $config->fbr_api_token_sandbox;
        $this->env = $config->fbr_env ?? 'sandbox';
    }

    protected function get(string $endpoint, array $query = []): array
    {
    try {
        $url = "https://gw.fbr.gov.pk/pdi/v1/" . ltrim($endpoint, '/');

        $response = Http::withHeaders($this->headers())
            ->timeout(30)
            ->get($url, $query);

        if ($response->failed()) {
            Log::error("FBR API GET failed: {$url}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    } catch (\Exception $e) {
        Log::error("FBR API GET exception: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
    }

    public function getItemDescCodes(): array
    {
        return $this->get('itemdesccode');
    }

    public function getDocTypeCodes(): array
    {
        return $this->get('doctypecode');
    }

    public function getProvinces(): array
    {
        return $this->get('provinces');
    }

    public function getSroItemCodes(): array
    {
        return $this->get('sroitemcode');
    }

    public function getTransTypeCodes(): array
    {
        return $this->get('transtypecode');
    }

    public function getUnitsOfMeasure(): array
    {
        return $this->get('uom');
    }

    // SRO Schedule
    public function getSroSchedule(array $params = []): array
    {
        return $this->get('SroSchedule', $params);
    }

    // Sale Type to Rate
    public function getSaleTypeToRate(array $params = []): array
    {
        return $this->get('SaleTypeToRate', $params);
    }

    // HS Code with UOM
    public function getHSUOM(array $params = []): array
    {
        return $this->get('HS_UOM', $params);
    }

    // SRO Item (with SRO ID & Date)
    public function getSroItem(array $params = []): array
    {
        return $this->get('SROItem', $params);
    }

    // STATL (check taxpayer registration status)
    public function checkSTATL(array $payload): array
    {
        return $this->postDist('statl', $payload);
    }

    // STATL Get_Reg_Type
    public function getRegType(array $payload): array
    {
        return $this->postDist('Get_Reg_Type', $payload);
    }

    // ------------------------------
    // Helper for POST calls to /dist
    // ------------------------------
    protected function postDist(string $endpoint, array $payload): array
    {
        try {
            $url = "https://gw.fbr.gov.pk/dist/v1/" . ltrim($endpoint, '/');
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->post($url, $payload);

            if ($response->failed()) {
                $body = $response->body();
                Log::error("FBR API POST failed: {$url}", [
                    'status' => $response->status(),
                    'body'   => $body,
                ]);
                return [
                    'success' => false,
                    'error'   => 'FBR service temporarily unavailable',
                    'debug'   => $body,
                ];
            }

            return [
                'success' => true,
                'data'    => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error("FBR API POST exception: " . $e->getMessage());
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

}