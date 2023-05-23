<?php

namespace App\Http\Controllers;

use App\Models\PadletUser;
use Illuminate\Http\Request;

class PadletUserController extends Controller
{
    public function getRoleInvitesByUserId($id)
    {
        $invites = PadletUser::with(['padlet'])
            ->where('user_id', $id)
            ->where('role', 'like', 'invite%')
            ->get();

        return response()->json($invites, 200);
    }

    public function save_update(Request $request)
    {
        $data = $request->all();

        $role = PadletUser::where('id', $data['id'])
            ->first();

        if ($role) {
            $role->update($data);
        } else {
            PadletUser::create($data);
        }

        return response()->json($data, 200);
    }

    public function delete($id)
    {
        $role = PadletUser::where('id', $id);

        if ($role) {
            $role->delete();
            return response()->json('Successfully deleted!', 200);
        }

        return response()->json('Role does not exist', 200);
    }
}
