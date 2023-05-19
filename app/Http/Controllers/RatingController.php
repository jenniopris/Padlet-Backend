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
        return $rating != null ? response()->json($rating, 200) : response()->json("Entry not found", 404);
    }

    public function getRatingByEntryIDAndUserID(string $entry_id, string $user_id): JsonResponse {
        $rating = Rating::where('entry_id', $entry_id)->where('user_id', $user_id)->with(['user'])->first();
        return $rating != null ? response()->json($rating, 200) : response()->json("Entry not found", 404);
    }

    public function save(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'entry_id' => 'required',
            'user_id' => 'required|unique:ratings,user_id,NULL,id,entry_id,' . $request->entry_id,
            'rating' => 'required|integer|min:1|max:5',
        ]);

        DB::beginTransaction();

        try {
            $rating = Rating::create($validatedData);

            // save user
            if (isset($request['user']) && is_array($request['user'])) {
                $user = User::create($request['user']);
                $rating->user()->associate($user);
            }

            DB::commit();
            // return a valid http response
            return response()->json($rating, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("saving rating failed: " . $e->getMessage(), 420);
        }
    }

    private function parseRequest(Request $request): Request
    {
        // get date and convert it - its in ISO 8601, e.g. "2018-01-01T23:00:00.000Z"
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }

    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $rating = Rating::with(['user'])->findOrFail($id);
            $request = $this->parseRequest($request);
            $rating->update($request->all());

            DB::commit();
            // return a valid http response
            return response()->json($rating, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating rating failed: " . $e->getMessage(), 420);
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
