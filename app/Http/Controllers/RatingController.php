<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RatingController extends Controller
{
    public function index(): JsonResponse
    {
        $ratings = Rating::with(['user'])->get();
        return response()->json($ratings, 200);
    }

    public function findByID(string $id): JsonResponse
    {
        $rating = Rating::where('id', $id)->with(['user'])->first();
        return $rating != null ? response()->json($rating, 200) : response()->json(null, 201);
    }

    public function getRatingByEntryIDAndUserID(string $entry_id, string $user_id): JsonResponse {
        $rating = Rating::where('entry_id', $entry_id)->where('user_id', $user_id)->with(['user'])->first();
        return $rating != null ? response()->json($rating, 200) : response()->json(null, 201);
    }

    public function save_update(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'entry_id' => 'required',
            'user_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        DB::beginTransaction();

        try {
            $existing = Rating::where('entry_id', $request->entry_id)
                ->where('user_id', $request->user_id)
                ->first();

            if($existing) {
                $existing->rating = $validatedData['rating'];
                $existing->save();
                DB::commit();
                return response()->json($existing, 201);
            } else {
                $rating = Rating::create($validatedData);
                DB::commit();
                return response()->json($rating, 201);
            }
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("saving rating failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(string $id): JsonResponse
    {
        $rating = Rating::where('id', $id)->first();
        if ($rating != null) {
            $rating->delete();
            return response()->json('rating (' . $id . ') successfully deleted', 200);
        } else {
            return response()->json('rating (' . $id . ') could not be deleted - does not exist', 422);
        }
    }
}
