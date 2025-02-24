<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\BannerController;

Route::prefix('v1')->group(function () {
    /** get all branches */
    Route::get('branches', [BranchController::class, 'index']);
    Route::post('branches', [BranchController::class, 'store']);
    Route::delete('branches/{id}', [BranchController::class, 'destroy']);

    /** Banner routes */
    Route::get('banner', [BannerController::class, 'get']);
    Route::post('banner', [BannerController::class, 'edit']);

});

// Route::get('v1/branches', function (Request $request) {
//     // Extract query parameters
//     $order = $request->query('order', 'id desc'); // Default order by id desc
//     $offset = $request->query('offset', 0); // Default offset 0
//     $limit = $request->query('limit', 10); // Default limit 10

//     // Fetch branches from database (replace `Branch` with your actual model)
//     $branches = \App\Models\Branch::orderByRaw($order)
//         ->offset($offset)
//         ->limit($limit)
//         ->get();

//     // Return response as JSON
//     return response()->json([
//         'status' => true,
//         'data' => $branches
//     ]);
// });
