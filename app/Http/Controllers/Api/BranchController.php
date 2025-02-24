<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller
{
    // List all branches with pagination

    public function index(Request $request)
    {
        $order = $request->query('order', 'id desc');

        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 10);

        // Fetch total count
        $total = Branch::count();

        // Fetch branches
        $branches = Branch::where('is_deleted', 0) // Example condition
        ->orderByRaw($order)
        ->offset($offset)
        ->limit($limit)
        ->get();


        // Calculate pagination details
        $currentPage = ($offset / $limit) + 1;
        $totalPages = ceil($total / $limit);

        return response()->json([
            'status' => true,
            'data' => $branches,
            'meta' => [
                'total' => $total,
                'page' => $currentPage,
                'limit' => $limit,
                'paging' => [
                    'total' => $total,
                    'per_page' => $limit,
                    'current_page' => $currentPage,
                    'total_pages' => $totalPages,
                    'has_next' => $currentPage < $totalPages,
                    'has_prev' => $currentPage > 1
                ]
            ]
        ]);
    }

    // Store a new branch
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:branches,name',
                'address' => 'required|string|max:500',
            ]);


            // Create branch
            $branch = Branch::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Branch created successfully',
                'data' => $branch
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    // Show details of a specific branch
    public function show($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json([
                'status' => false,
                'message' => 'Branch not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $branch
        ]);
    }

    // Update an existing branch
    public function update(Request $request, $id)
    {
        try {
            // Find branch
            $branch = Branch::find($id);
            if (!$branch) {
                return response()->json([
                    'status' => false,
                    'message' => 'Branch not found'
                ], 404);
            }

            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
            ]);

            // Update branch
            $branch->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Branch updated successfully',
                'data' => $branch
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    // Delete a branch
    public function destroy($id)
    {

        $is_deleted = Branch::find($id)->is_deleted;
        $soft_delete = ($is_deleted == 0) ? 1 : 0;
        $branch = Branch::where('id',$id)->update(['is_deleted'=>$soft_delete]);
        if (!$branch) {
            return response()->json([
                'status' => false,
                'message' => 'Branch not found'
            ], 404);
        }

        // $branch->delete();

        return response()->json([
            'status' => true,
            'message' => 'Branch deleted successfully'
        ]);
    }
}
