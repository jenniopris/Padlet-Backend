<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        $comments = Comment::with(['user'])->get();
        return response()->json($comments, 200);
    }

    public function findByID(string $id): JsonResponse
    {
        $comment = Comment::where('id', $id)->with(['user'])->first();
        return $comment != null ? response()->json($comment, 200) : response()->json("Entry not found", 404);
    }

    public function getCommentByEntryID(string $entry_id): JsonResponse
    {
        $comment = Comment::where('entry_id', $entry_id)->with(['user'])->get();
        return $comment != null ? response()->json($comment, 200) : response()->json("Entry not found", 404);
    }

    public function save(Request $request): JsonResponse
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $comment = Comment::create($request->all());

            // save user
            if (isset($request['comment']) && is_array($request['comment'])) {
                $user = User::create($request['user']);
                $comment->user()->associate($user);
            }

            $comment->save();
            DB::commit();
            // return a vaild http response
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("saving comment failed: " . $e->getMessage(), 420);
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
            $comment = Comment::with(['user'])->findOrFail($id);
            $request = $this->parseRequest($request);
            $comment->update($request->all());

            DB::commit();
            // return valid http response
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating comment failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(string $id): JsonResponse
    {
        $comment = Comment::where('id', $id)->first();
        if ($comment != null) {
            $comment->delete();
            return response()->json('comment (' . $id . ') successfully deleted', 200);
        } else {
            return response()->json('comment (' . $id . ') could not be deleted - does not exist', 422);
        }
    }
}
