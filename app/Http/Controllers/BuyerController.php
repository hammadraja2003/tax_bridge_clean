<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $query = Buyer::query();
        // 🔎 Filter by Client Type
        if ($request->filled('byr_type')) {
            $query->where('byr_type', $request->byr_type);
        }
        // 🔎 Search filter (name, CNIC, address, etc.)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('byr_name', 'like', "%{$search}%")
                    ->orWhere('byr_ntn_cnic', 'like', "%{$search}%")
                    ->orWhere('byr_address', 'like', "%{$search}%");
            });
        }
        // ✅ Now apply filters
        $buyers = $query->latest()->paginate(10);
        // 🔐 Tampering check
        foreach ($buyers as $buyer) {
            $calculatedHash = $buyer->generateHash();
            $buyer->tampered = $calculatedHash !== $buyer->hash;
        }
        return view('buyers.index', compact('buyers'));
    }
    public function fetch($id)
    {
        return response()->json(Buyer::findOrFail($id));
    }
    public function create()
    {
        return view('buyers.create');
    }
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $validated = $request->validate([
    //             'byr_name' => 'required|string|max:255',
    //             'byr_type' => 'required|integer',
    //             'byr_id_type' => [
    //                 'nullable',
    //                 'required_if:byr_type,1',
    //                 'in:NTN,CNIC'
    //             ],
    //             'byr_ntn_cnic' => [
    //                 'nullable',
    //                 'required_if:byr_type,1',
    //                 function ($attribute, $value, $fail) use ($request) {
    //                     if ($request->byr_id_type === 'NTN' && !preg_match('/^[0-9]{7}$/', $value)) {
    //                         $fail('Buyer NTN must be exactly 7 digits.');
    //                     }
    //                     if ($request->byr_id_type === 'CNIC' && !preg_match('/^[0-9]{13}$/', $value)) {
    //                         $fail('Buyer CNIC must be exactly 13 digits (without dashes).');
    //                     }
    //                 }
    //             ],
    //             'byr_address' => 'required|string',
    //             'byr_province' => 'required|string',
    //             'byr_account_title' => 'nullable|string|max:255',
    //             'byr_account_number' => 'nullable|string|max:255',
    //             'byr_reg_num' => 'nullable|string|max:255',
    //             'byr_contact_num' => 'nullable|string|max:20',
    //             'byr_contact_person' => 'nullable|string|max:255',
    //             'byr_IBAN' => 'nullable|string|max:255',
    //             'byr_acc_branch_name' => 'nullable|string|max:255',
    //             'byr_acc_branch_code' => 'nullable|string|max:255',
    //             'byr_logo' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
    //         ]);
    //         //Simple Code 
    //         // if ($request->hasFile('byr_logo')) {
    //         //     $file = $request->file('byr_logo');
    //         //     $extension = $file->getClientOriginalExtension();
    //         //     $filename = time() . '.' . $extension;
    //         //     $path = getUploadPath('buyer_images');
    //         //     if (!file_exists($path)) {
    //         //         mkdir($path, 0777, true);
    //         //     }
    //         //     $file->move($path, $filename);
    //         //     $validated['byr_logo'] = $filename;
    //         // } else {
    //         //     $validated['byr_logo'] = '';
    //         // }
    //         //Dynamic Storage Code
    //         $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

    //         if ($request->hasFile('byr_logo')) {
    //             $file = $request->file('byr_logo');
    //             $extension = $file->getClientOriginalExtension();
    //             $filename = time() . '.' . $extension;
    //             $folder = 'buyer_images';

    //             $options = ($disk === 's3') ? ['visibility' => 'public'] : [];

    //             $path = Storage::disk($disk)->putFileAs($folder, $file, $filename, $options);

    //             // DB will contain: buyer_images/123.png
    //             $validated['byr_logo'] = $path;
    //         } else {
    //             $validated['byr_logo'] = '';
    //         }


    //         $normalized = collect($validated)->map(fn($v) => $v ?? '')->toArray();
    //         $buyer = Buyer::create($normalized);
    //         logActivity(
    //             'add',
    //             'Added new client: ' . $buyer->byr_name,
    //             $buyer->toArray(),
    //             $buyer->id,
    //             'buyers'
    //         );
    //         DB::commit();
    //         return redirect()->route('buyers.index')
    //             ->with('message', 'Client created successfully.');
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         return back()
    //             ->withInput()
    //             ->withErrors(['toast_error' =>  $e->getMessage()]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()
    //             ->withInput()
    //             ->withErrors(['toast_error' =>  $e->getMessage()]);
    //     }
    // }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'byr_name' => 'required|string|max:255',
                'byr_type' => 'required|integer',
                'byr_id_type' => [
                    'nullable',
                    'required_if:byr_type,1',
                    'in:NTN,CNIC'
                ],
                'byr_ntn_cnic' => [
                    'nullable',
                    'required_if:byr_type,1',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->byr_id_type === 'NTN' && !preg_match('/^[0-9]{7}$/', $value)) {
                            $fail('Buyer NTN must be exactly 7 digits.');
                        }
                        if ($request->byr_id_type === 'CNIC' && !preg_match('/^[0-9]{13}$/', $value)) {
                            $fail('Buyer CNIC must be exactly 13 digits (without dashes).');
                        }
                    }
                ],
                'byr_address' => 'required|string',
                'byr_province' => 'required|string',
                'byr_account_title' => 'nullable|string|max:255',
                'byr_account_number' => 'nullable|string|max:255',
                'byr_reg_num' => 'nullable|string|max:255',
                'byr_contact_num' => 'nullable|string|max:20',
                'byr_contact_person' => 'nullable|string|max:255',
                'byr_IBAN' => 'nullable|string|max:255',
                'byr_acc_branch_name' => 'nullable|string|max:255',
                'byr_acc_branch_code' => 'nullable|string|max:255',
                'byr_logo' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',

            ]);

            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            try {
                Log::info('Buyer logo upload started', [
                    'disk' => $disk,
                    'has_file' => $request->hasFile('byr_logo'),
                ]);

                if ($request->hasFile('byr_logo')) {
                    $file = $request->file('byr_logo');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $folder = 'buyer_images';

                    // $options = ($disk === 's3') ? ['visibility' => 'public'] : [];
                    $options = [];
                    
                    Log::info('S3 config check', [
                        'bucket' => config('filesystems.disks.s3.bucket'),
                        'region' => config('filesystems.disks.s3.region'),
                        'url' => config('filesystems.disks.s3.url'),
                    ]);


                    $path = Storage::disk($disk)->putFileAs($folder, $file, $filename, $options);

                    if ($path) {
                        Log::info('File uploaded successfully', ['path' => $path]);
                        $validated['byr_logo'] = $path;
                    } else {
                        Log::error('File upload failed: putFileAs returned false');
                        $validated['byr_logo'] = '';
                    }
                } else {
                    Log::warning('No byr_logo file found in request');
                    $validated['byr_logo'] = '';
                }
            } catch (\Throwable $e) {
                Log::error('Error during buyer logo upload', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $validated['byr_logo'] = '';
            }

            $normalized = collect($validated)->map(fn($v) => $v ?? '')->toArray();
            $buyer = Buyer::create($normalized);

            logActivity(
                'add',
                'Added new client: ' . $buyer->byr_name,
                $buyer->toArray(),
                $buyer->id,
                'buyers'
            );

            DB::commit();

            return redirect()->route('buyers.index')
                ->with('message', 'Client created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation failed during buyer creation', [
                'errors' => $e->errors(),
            ]);
            return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Unexpected error during buyer creation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        }
    }    public function edit($id)
    {
        $decryptedId = Crypt::decryptString($id);
        $buyer = Buyer::findOrFail($decryptedId);
        return view('buyers.edit', compact('buyer'));
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'byr_name' => 'required|string|max:255',
                'byr_type' => 'required|integer',
                'byr_id_type' => [
                    'nullable',
                    'required_if:byr_type,1',
                    'in:NTN,CNIC'
                ],
                'byr_ntn_cnic' => [
                    'nullable',
                    'required_if:byr_type,1',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->byr_id_type === 'NTN' && !preg_match('/^[0-9]{7}$/', $value)) {
                            $fail('Buyer NTN must be exactly 7 digits.');
                        }
                        if ($request->byr_id_type === 'CNIC' && !preg_match('/^[0-9]{13}$/', $value)) {
                            $fail('Buyer CNIC must be exactly 13 digits (without dashes).');
                        }
                    }
                ],
                'byr_address' => 'required|string',
                'byr_province' => 'required|string',
                'byr_account_title' => 'nullable|string|max:255',
                'byr_account_number' => 'nullable|string|max:255',
                'byr_reg_num' => 'nullable|string|max:255',
                'byr_contact_num' => 'nullable|string|max:20',
                'byr_contact_person' => 'nullable|string|max:255',
                'byr_IBAN' => 'nullable|string|max:255',
                'byr_acc_branch_name' => 'nullable|string|max:255',
                'byr_acc_branch_code' => 'nullable|string|max:255',
                'byr_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $buyer = Buyer::findOrFail($id);
            // Keep old data for hash comparison
            $oldData = $buyer->toArray();

            // $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            // if ($request->hasFile('byr_logo')) {
            //     $folder = 'buyer_images';

            //     // Delete old file if exists
            //     if ($buyer->byr_logo && Storage::disk($disk)->exists($buyer->byr_logo)) {
            //         Storage::disk($disk)->delete($buyer->byr_logo);
            //     }

            //     $file = $request->file('byr_logo');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' . $extension;

            //     $options = ($disk === 's3') ? ['visibility' => 'public'] : [];
            //     // Upload new file
            //     $path = Storage::disk($disk)->putFileAs($folder, $file, $filename, $options);
            //     // Save relative path (e.g. buyer_images/123.png)
            //     $validated['byr_logo'] = $path;
            // } else {
            //     // Keep existing logo if no new file uploaded
            //     $validated['byr_logo'] = $buyer->byr_logo ?? '';
            // }

            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            if ($request->hasFile('byr_logo')) {
                $folder = 'buyer_images';

                // ✅ Delete old file if it exists
                if (!empty($buyer->byr_logo) && Storage::disk($disk)->exists($buyer->byr_logo)) {
                    Storage::disk($disk)->delete($buyer->byr_logo);
                }

                $file      = $request->file('byr_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;

                // ⚡ Use your confirmed working logic → no extra options
                $path = Storage::disk($disk)->putFileAs($folder, $file, $filename);

                if ($path) {
                    // ✅ Save relative path (e.g. buyer_images/123.png)
                    $validated['byr_logo'] = $path;
                } else {
                    // ❌ Upload failed → keep old one
                    $validated['byr_logo'] = $buyer->byr_logo ?? '';
                    Log::error('❌ Buyer logo update failed', [
                        'disk'     => $disk,
                        'folder'   => $folder,
                        'filename' => $filename,
                    ]);
                }
            } else {
                // ⚡ Keep existing logo if no new file uploaded
                $validated['byr_logo'] = $buyer->byr_logo ?? '';
            }



            $normalized = collect($validated)->map(fn($v) => $v ?? '')->toArray();
            $buyer->update($normalized);
            logActivity(
                'update',
                'Updated client: ' . $buyer->byr_name,
                ['old' => $oldData, 'new' => $buyer->toArray()],
                $buyer->id,
                'buyers'
            );
            DB::commit();
            return redirect()->route('buyers.index')
                ->with('message', 'Client updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
            return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        }
    }
    public function delete($id)
    {
        $buyer = Buyer::findOrFail($id);
        $oldData = $buyer->toArray();
        $buyer->delete();
        // ✅ Log delete activity
        logActivity(
            'delete',
            'Deleted client: ' . $oldData['byr_name'],
            $oldData,
            $id,
            'buyers'
        );
        return redirect()->route('buyers.index')->with('message', 'Client deleted successfully.');
    }
}
