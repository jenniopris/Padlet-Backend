<?php

namespace App\Http\Controllers;

use App\Models\PadletUser;
use App\Models\User;
use App\Models\Padlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PadletUserController extends Controller
{
    public function getRoleInvitesByUserId($id): JsonResponse
    {
        $invites = PadletUser::with(['padlet'])
            ->where('user_id', $id)
            ->where('role', 'like', 'invite%')
            ->get();

        $invites->each(function ($invite) {
            $padlet = Padlet::with('user')
                ->where('id', $invite->padlet_id)
                ->first();
            $invite->invited_by = $padlet->user->first_name . ' ' . $padlet->user->last_name;
        });

        return response()->json($invites, 200);
    }

    public function save_update(Request $request): JsonResponse
    {
        $data = $request->all();

        $role = PadletUser::where('padlet_id', $data['padlet_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($role) {
            $role->update($data);
        } else {
            PadletUser::create($data);
        }

        return response()->json($data, 200);
    }

    public function delete($id): JsonResponse
    {
        $role = PadletUser::where('id', $id);

        if ($role) {
            $role->delete();
            return response()->json('Successfully deleted!', 200);
        }

        return response()->json('Role does not exist', 200);
    }
}
