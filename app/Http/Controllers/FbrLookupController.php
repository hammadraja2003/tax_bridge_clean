<?php
namespace App\Http\Controllers;

use App\Services\FbrInvoiceService;
use Illuminate\Http\Request;

class FbrLookupController extends Controller
{
    protected FbrInvoiceService $fbr;

    public function __construct(FbrInvoiceService $fbr)
    {
        $this->fbr = $fbr;
    }

    public function fetch(Request $request)
    {
        $type = $request->input('type');

        switch ($type) {
            case 'provinces':
                $response = $this->fbr->getProvinces();
                break;
            case 'items':
                $response = $this->fbr->getItemDescCodes();
                break;
            case 'uom':
                $response = $this->fbr->getUnitsOfMeasure();
                break;
            case 'doctype':
                $response = $this->fbr->getDocTypeCodes();
                break;
            case 'sroitem':
                $response = $this->fbr->getSroItemCodes();
                break;
            case 'transtype':
                $response = $this->fbr->getTransTypeCodes();
                break;
            case 'sroschedule':
                $response = $this->fbr->getSroSchedule($request->all());
                break;
            case 'saletypetorate':
                $response = $this->fbr->getSaleTypeToRate($request->all());
                break;
            case 'hsuom':
                $response = $this->fbr->getHSUOM($request->all());
                break;
            case 'sroitemdetail':
                $response = $this->fbr->getSroItem($request->all());
                break;
            case 'statl':
                $response = $this->fbr->checkSTATL($request->all());
                break;
            case 'regtype':
                $response = $this->fbr->getRegType($request->all());
                break;

            default:
                $response = ['success' => false, 'error' => 'Invalid type'];
        }

        return response()->json($response);
    }
}
