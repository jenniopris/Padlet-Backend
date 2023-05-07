<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Padlet;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent;

class PadletController extends Controller
{
    public function index(): JsonResponse
    {
        $padlets = Padlet::with('entry', 'user')->get();
        return response()->json($padlets, 200);
    }

    public function findByPadletID($id): JsonResponse
    {
        $padlet = Padlet::with('entry', 'user')
            ->where('id', $id)->first();
        return response()->json($padlet, 200);
    }

    public function findByUserID($id): JsonResponse
    {
        $padlets = Padlet::with('entry', 'user')->where('user_id', $id)->get();
        return response()->json($padlets, 200);
    }

    public function save(Request $request): JsonResponse
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $padlet = Padlet::create($request->all());

            // save entries
            if (isset($request['entries']) && is_array($request['entries'])) {
                foreach ($request['entries'] as $entry) {
                    $entry = Entry::create($entry);
                    $padlet->entry()->save($entry);
                }
            }
            DB::commit();
            // return valid http response
            return response()->json($padlet, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("saving padlet failed: " . $e->getMessage(), 420);
        }
    }

    /**
     * modify / convert values if needed
     * @param Request $request
     * @return Request
     * @throws \Exception
     */
    private function parseRequest(Request $request): Request
    {
        // get date and convert it - its in ISO 8601, e.g. "2018-01-01T23:00:00.000Z"
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }

    public function update(Request $request, string $id): JsonResponse {
        DB::beginTransaction();
        try {
            $padlet = Padlet::with('entry')->findOrFail($id);
            $request = $this->parseRequest($request);
            $padlet->update($request->all());

            // delete old entries
            foreach ($padlet->entry as $entry) {
                $entry->delete();
            }

            // save new entries
            if (isset($request['entries']) && is_array($request['entries'])) {
                foreach ($request['entries'] as $entry) {
                    $entry = Entry::create($entry);
                    $padlet->entry()->save($entry);
                }
            }
            DB::commit();
            // return valid http response
            return response()->json($padlet, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating padlet failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(string $id): JsonResponse
    {
        $padlet = Padlet::where('id', $id)->first();
        if ($padlet != null) {
            $padlet->delete();
            return response()->json('padlet (' . $id . ') successfully deleted', 200);
        } else {
            return response()->json('padlet (' . $id . ') could not be deleted - does not exist', 422);
        }
    }
}
