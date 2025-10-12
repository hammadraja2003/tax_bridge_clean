<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->paginate(10);
        // Check for tampering
        foreach ($items as $item) {
            $calculatedHash = md5(
                $item->item_hs_code .
                    $item->item_description .
                    $item->item_price .
                    $item->item_tax_rate .
                    $item->item_uom
            );
            $item->tampered = $calculatedHash !== $item->hash;
        }
        return view('items.index', compact('items'));
    }
    public function create()
    {
        return view('items.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'item_description' => 'required|string',
            'item_price'       => 'required|numeric',
            'item_tax_rate'    => 'required|numeric',
            'item_uom'         => 'required|string|max:50',
        ]);
        DB::beginTransaction();
        try {
            $item = Item::create($request->all());
            logActivity(
                'add',
                'Added new item: ' . $item->item_description,
                $item->toArray(),
                $item->id,
                'items'
            );
            DB::commit();
            return redirect()
                ->route('items.index')
                ->with('message', 'Item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_description' => 'required|string|max:255',
            'item_price'       => 'required|numeric',
            'item_tax_rate'    => 'required|numeric',
            'item_uom'         => 'required|string|max:50',
            'item_hs_code'     => 'nullable|string|max:20',
        ]);
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            $oldData = $item->toArray();
            $item->update($request->all());
            // Log activity
            logActivity(
                'update',
                'Updated item: ' . $item->item_description,
                [
                    'old' => $oldData,
                    'new' => $item->toArray(),
                ],
                $item->id,
                'items'
            );
            DB::commit();
            return redirect()
                ->route('items.index')
                ->with('message', 'Item updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update item. Please try again.']);
        }
    }
    public function delete($id)
    {
        $item = Item::findOrFail($id);
        logActivity(
            'delete',
            'Deleted item: ' . $item->item_description,
            $item->toArray(),
            $item->id,
            'items'
        );
        $item->delete();
        return redirect()->route('items.index')->with('message', 'Item deleted successfully.');
    }
}