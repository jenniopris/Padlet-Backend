<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\User;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EntryController extends Controller
{
    public function index(): JsonResponse
    {
        $entries = Entry::with(['user', 'rating', 'comment'])->get();
        return response()->json($entries, 200);
    }

    public function findByID(string $id): JsonResponse
    {
        $entry = Entry::where('id', $id)->with(['user', 'rating', 'comment'])->first();
        return $entry != null ? response()->json($entry, 200) : response()->json("Entry not found", 404);
    }

    public function findBySearchTerm(string $searchTerm): JsonResponse
    {
        $entries = Entry::with(['user', 'rating', 'comment'])
            ->where('padlet_id', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('user_id', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('type', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('content', 'LIKE', '%' . $searchTerm . '%')
            ->get();
        return response()->json($entries, 200);
    }

    public function save(Request $request): JsonResponse
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $entry = Entry::create($request->all());

            // save user
            if (isset($request['user']) && is_array($request['user'])) {
                $user = User::create($request['user']);
                $entry->user()->associate($user);
            }

            // save ratings
            if (isset($request['ratings']) && is_array($request['ratings'])) {
                foreach ($request['ratings'] as $rating) {
                    $rating = Rating::create($rating);
                    $entry->rating()->save($rating);
                }
            }

            // save comments
            if (isset($request['comments']) && is_array($request['comments'])) {
                foreach ($request['comments'] as $comment) {
                    $comment = Comment::create($comment);
                    $entry->comment()->save($comment);
                }
            }

            $entry->save();
            DB::commit();
            // return a vaild http response
            return response()->json($entry, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("saving entry failed: " . $e->getMessage(), 420);
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
            $entry = Entry::with(['user', 'rating', 'comment'])->findOrFail($id);
            $request = $this->parseRequest($request);
            $entry->update($request->all());

            DB::commit();
            // return valid http response
            return response()->json($entry, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating entry failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(string $id): JsonResponse
    {
        $entry = Entry::where('id', $id)->first();
        if ($entry != null) {
            $entry->delete();
            return response()->json('entry (' . $id . ') successfully deleted', 200);
        } else {
            return response()->json('entry (' . $id . ') could not be deleted - does not exist', 422);
        }
    }
}
