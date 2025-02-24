<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\banner;

class BannerController extends Controller
{
    //get
    public function get(Request $request){
         try {
            //code...
            $banner = banner::find(1);
            if (!$banner) {
                return response()->json([
                    'status' => false,
                    'message' => 'Banner not found'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Banner updated successfully',
                'data' => $banner
            ]);

         } catch (\Throwable $th) {
            //throw $th;
            echo $th->getMessage();
         }
    }

    public function edit(Request $request){
        try {
           //code...
           $banner = banner::find(1);
           if (!$banner) {
               return response()->json([
                   'status' => false,
                   'message' => 'Banner not found'
               ], 404);
           }

           // Validate request
           $validated = $request->validate([
               'head' => 'string|max:255',
               'sub_head' => 'string|max:500',
           ]);

           $head = $request->head;
           $sub_head = $request->sub_head;
           // Update branch
           $banner->update([
               'head' => $head,
               'sub_head' => $sub_head
           ]);

           return response()->json([
               'status' => true,
               'message' => 'Banner updated successfully',
               'data' => $banner
           ]);

        } catch (\Throwable $th) {
           //throw $th;
           echo $th->getMessage();
        }
   }
}
