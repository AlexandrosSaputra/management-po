<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MasterUserController extends Controller
{
    public function index()
    {
        if (!Auth::user()->status) {
            return abort(403 , 'User disabled!, hubungi admin untuk akses');
        }

        if (Auth::user()->level != 'admin') {
            return redirect('/preorder');
        }

        $filterNama = request()->filterNama;
        $filterId = request()->filterId;

        $users = User::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $variables = [
            'users' => $users,
            'filterNama' => $filterNama,
            'filterId' => $filterId
        ];

        return view('master-user.index', $variables);
    }

    public function show(User $user)
    {
        if (Auth::user()->level != 'admin') {
            return redirect('/penawaran');
        }

        $cabangs = Cabang::all();

        return view('master-user.show', ['user' => $user, 'cabangs' => $cabangs]);
    }

    public function update(User $user)
    {
        if (Auth::user()->level == 'admin') {
            $status = filter_var(request()->input('status'), FILTER_VALIDATE_BOOLEAN);

            request()->validate([
                'nama' => 'required|string',
                'telepon' => 'required|regex:/^62[0-9]{8,12}$/',
                'id_token' => 'required',
                'level' => 'required',
                'cabang_id' => 'required',
            ]);

            $user->update([
                'nama' => request()->nama,
                'telepon' => request()->telepon,
                'id_token' => request()->id_token,
                'status' => $status,
                'level' => request()->level,
                'cabang_id' => request()->cabang_id,
            ]);

        }

        return back()->with('message', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->level == 'admin') {
            $user->delete();
        } else {
            abort(403, 'Unauthorized!');
        }

        return redirect('/master-user')->with('message', 'User berhasil dihapus');
    }
}
