<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Padlet;
use App\Models\Entry;
use App\Models\Comment;
use App\Models\Rating;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with(['padlet', 'entry', 'comment', 'rating'])->get();
        return response()->json($users, 200);
    }

    public function findByUserID(string $id): JsonResponse
    {
        $user = User::where('id', $id)->with(['padlet', 'entry', 'comment', 'rating'])->first();
        return $user != null ? response()->json($user, 200) : response()->json("User not found", 404);
    }

    public function findBySearchTerm(string $searchTerm): JsonResponse
    {
        $users = User::with(['padlet', 'entry', 'comment', 'rating'])
            ->where('first_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
            ->get();
        return response()->json($users, 200);
    }

    public function save(Request $request): JsonResponse
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $user = User::create($request->all());

            // save padlets
            if (isset($request['padlets']) && is_array($request['padlets'])) {
                foreach ($request['padlets'] as $padlet) {
                    $padlet = Padlet::create($padlet);
                    $user->padlet()->save($padlet);
                }
            }

            // save entries
            if (isset($request['entries']) && is_array($request['entries'])) {
                foreach ($request['entries'] as $entry) {
                    $entry = Entry::create($entry);
                    $user->entry()->save($entry);
                }
            }

            // save comments
            if (isset($request['comments']) && is_array($request['comments'])) {
                foreach ($request['comments'] as $comment) {
                    $comment = Comment::create($comment);
                    $user->comment()->save($comment);
                }
            }

            // save ratings
            if (isset($request['ratings']) && is_array($request['ratings'])) {
                foreach ($request['ratings'] as $rating) {
                    $rating = Rating::create($rating);
                    $user->rating()->save($rating);
                }
            }

            DB::commit();
            // return valid http response
            return response()->json($user, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("saving user failed: " . $e->getMessage(), 420);
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
            $user = User::with(['padlet', 'entry', 'comment', 'rating'])->findOrFail($id);
            $request = $this->parseRequest($request);
            $user->update($request->all());

            DB::commit();
            // return valid http response
            return response()->json($user, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating user failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(string $id): JsonResponse
    {
        $user = User::where('id', $id)->first();
        if ($user != null) {
            $user->delete();
            return response()->json('user (' . $id . ') successfully deleted', 200);
        } else {
            return response()->json('user (' . $id . ') could not be deleted - does not exist', 422);
        }
    }
}
