<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function update(Request $request, User $user)
    {
        if (Auth::user()->level == 'admin') {
            $status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);

            $validated = $request->validate([
                'nama' => 'required|string',
                'telepon' => 'required|regex:/^62[0-9]{8,12}$/',
                'level' => 'required',
                'cabang_id' => 'required',
                'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->id)],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]);

            $user->update([
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'],
                'status' => $status,
                'level' => $validated['level'],
                'cabang_id' => $validated['cabang_id'],
                'username' => $validated['username'],
                'email' => $validated['email'],
            ]);

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
                $user->save();
            }

        }

        return redirect('/master-user')->with('message', 'Data user berhasil diperbarui.');
    }

    public function updateCredentials(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('message', 'Kredensial berhasil diupdate');
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
