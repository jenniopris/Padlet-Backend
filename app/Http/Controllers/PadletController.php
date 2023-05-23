<?php

namespace App\Http\Controllers;

use App\Models\PadletUser;
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
        $user = auth()->user();

        $padlets = Padlet::with('entry', 'user', 'padletUser')
            ->where('is_public', true)
            ->orWhere('user_id', $user?->id)
            ->orWhereHas('padletUser', function ($query) use ($user) {
                $query->where('user_id', $user?->id)
                    ->where('role', 'viewer')
                    ->orWhere('role', 'editor')
                    ->orWhere('role', 'owner');
            })
            ->get();

        if ($user) {
            $padlets->each(function ($padlet) use ($user) {
                $padlet->isEditableByCurrentUser = $padlet->user_id === $user->id;
                $padlet->padletUser->each(function ($padletUserRole) use ($padlet, $user) {
                    if ($padletUserRole->user_id === $user->id
                        && ($padletUserRole->role === 'editor'
                        || $padletUserRole->role === 'owner')) {
                        $padlet->isEditableByCurrentUser = true;
                    }
                });
            });
        }

        return response()->json($padlets, 200);
    }

    public function findByPadletID($id): JsonResponse
    {
        $user = auth()->user();
        $padletUserRole = PadletUser::where('padlet_id', $id)
            ->where('user_id', $user?->id)
            ->value('role', '');

        $padlet = Padlet::with('entry', 'user')
            ->where('id', $id)->first();

        if (!$padlet) {
            return response()->json("Padlet not found", 404);
        }

        if (!($padlet->is_public || $padletUserRole == 'viewer' || $padletUserRole == 'editor' || $padletUserRole == 'owner')){
            return response()->json("You are not allowed to perform this action", 401);
        }

        if ($user) {
            $padlet->isEditableByCurrentUser = $padlet->user_id === $user->id;
            $padlet->padletUser->each(function ($padletUserRole) use ($padlet, $user) {
                if ($padletUserRole->user_id === $user->id
                    && ($padletUserRole->role === 'editor'
                        || $padletUserRole->role === 'owner')) {
                    $padlet->isEditableByCurrentUser = true;
                }
            });
        }

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

        $user = auth()->user();
        $padletUserRole = PadletUser::where('padlet_id', $id)
            ->where('user_id', $user?->id)
            ->value('role', '');

        DB::beginTransaction();
        try {
            $padlet = Padlet::with('entry')->findOrFail($id);

            if (!$padlet) {
                return response()->json("Padlet not found", 404);
            }

            if (!($padlet->is_public || $padletUserRole == 'viewer' || $padletUserRole == 'editor' || $padletUserRole == 'owner')){
                return response()->json("You are not allowed to perform this action", 401);
            }

            $request = $this->parseRequest($request);
            $padlet->update($request->all());

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
        $user = auth()->user();
        $padletUserRole = PadletUser::where('padlet_id', $id)
            ->where('user_id', $user?->id)
            ->value('role', '');

        $padlet = Padlet::where('id', $id)->first();

        if (!$padlet) {
            return response()->json("Padlet not found", 404);
        }

        if (!($padlet->is_public || $padletUserRole == 'viewer' || $padletUserRole == 'editor' || $padletUserRole == 'owner')){
            return response()->json("You are not allowed to perform this action", 401);
        }

        $padlet->delete();
        return response()->json('Padlet (' . $id . ') successfully deleted', 200);
    }
}
