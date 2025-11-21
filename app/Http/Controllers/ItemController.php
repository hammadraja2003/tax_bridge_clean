<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->paginate(10);
        foreach ($items as $item) {
            $calculatedHash = md5(
                $item->item_hs_code .
                    $item->item_description .
                    number_format($item->item_price, 2, '.', '') .
                    $item->item_tax_rate .
                    $item->item_uom
            );
            $item->tampered = $calculatedHash !== $item->hash;
        }
        if (isApiRequest()) {
            return paginatedResponse($items, 'Items Data Fetched');
        }
        return view('items.index', compact('items'));
    }
    public function create()
    {
        return view('items.create');
    }
    public function store(Request $request)
    {
        $isApiRequest = isApiRequest();
        $rules = [
            'item_description' => 'required|string|max:255',
            'item_price'       => 'required|numeric',
            'item_tax_rate'    => 'required|numeric',
            'item_uom'         => 'required|string|max:30',
            'item_hs_code'     => 'required|string|max:20',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($isApiRequest) {
                return errorResponse($validator->errors(), 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $item = Item::create($request->all());
            logActivity(
                'add',
                'Added new item: ' . $item->item_description,
                $item->toArray(),
                $item->item_id,
                'items'
            );
            DB::commit();
            if ($isApiRequest) {
                return successResponse($item, 200, 'Item created successfully');
            }
            return redirect()
                ->route('items.index')
                ->with('message', 'Item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($isApiRequest) {
                return errorResponse($e->getMessage(), 500);
            }
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to create item. Please try again.']);
        }
    }
    public function edit($id)
    {
        $decryptedId = Crypt::decryptString($id);
        $item = Item::findOrFail($decryptedId);
        return view('items.edit', compact('item'));
    }
    public function update(Request $request)
    {
        $isApiRequest = isApiRequest();
        $rules = [
            'item_id'          => 'required',
            'item_description' => 'required|string|max:255',
            'item_price'       => 'required|numeric',
            'item_tax_rate'    => 'required|numeric',
            'item_uom'         => 'required|string|max:30',
            'item_hs_code'     => 'required|string|max:20',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($isApiRequest) {
                return errorResponse($validator->errors(), 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $item_id = $request->item_id;
            if (!$isApiRequest) {
                $item_id = Crypt::decryptString($request->item_id);
            }
            $item = Item::findOrFail($item_id);
            $oldData = $item->toArray();
            $item->update($request->all());
            logActivity(
                'update',
                'Updated item: ' . $item->item_description,
                [
                    'old' => $oldData,
                    'new' => $item->toArray(),
                ],
                $item->item_id,
                'items'
            );
            DB::commit();
            if ($isApiRequest) {
                return successResponse(
                    data: $item,
                    status: 200,
                    message: 'Item updated successfully'
                );
            }
            return redirect()
                ->route('items.index')
                ->with('message', 'Item updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($isApiRequest) {
                return errorResponse(
                    message: $e->getMessage(),
                    status: 500
                );
            }
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update item. Please try again.']);
        }
    }
    public function delete(Request $request)
    {
        try {
            $isApiRequest = isApiRequest();
            $item_id = $request->item_id;
            if (!$isApiRequest) {
                $item_id = Crypt::decryptString($request->item_id);
            }
            $item = Item::findOrFail($item_id);
            //  Check if this item is already used in any invoice detail
            if ($item->invoiceDetails()->exists()) {
                $msg = 'Item cannot be deleted because it is already used in invoices';
                if ($isApiRequest) {
                    return errorResponse($msg, 400);
                }
                $validator = Validator::make([], []);
                $validator->errors()->add(
                    'toast_error',
                    $msg
                );
                return redirect()
                    ->route('items.index')
                    ->withErrors($validator);
            }
            // Proceed with delete
            $oldData = $item->toArray();
            $item->delete();
            logActivity(
                'delete',
                'Deleted item: ' . $oldData['item_description'],
                $oldData,
                $item_id,
                'items'
            );
            if ($isApiRequest) {
                return successResponse([], 200, 'Item deleted successfully');
            }
            return redirect()
                ->route('items.index')
                ->with('message', 'Item deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($isApiRequest) {
                return errorResponse('Item not found', 404);
            }
            return redirect()
                ->route('items.index')
                ->withErrors(['message' => 'Item not found']);
        } catch (\Exception $e) {
            if ($isApiRequest) {
                return errorResponse($e->getMessage(), 500);
            }
            return redirect()
                ->route('items.index')
                ->withErrors(['message' => $e->getMessage()]);
        }
    }
    public function fetchItem(Request $request)
    {
        try {
            $item_id = $request->item_id;
            $buyer = Item::findOrFail($item_id);
            return successResponse(
                $buyer,
                status: 200,
                message: 'Item fetched successfully'
            );
        } catch (\Exception $e) {
            return errorResponse(
                message: 'Item not found',
                status: 404
            );
        }
    }
}
