<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Wirehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'title' => 'Pegawai',
            'users' => User::all()
        ];
        return view('admin.users.index', $data);
    }
    public function getUsersDataTable()
    {
        $users = User::with(['shop', 'wirehouse'])->orderByDesc('id')->get();

        return Datatables::of($users)
            ->addColumn('name', function ($user) {
                $disabled = $user->is_disabled == 1 ? '<span class="badge bg-danger">Disabled</span>' : '<span class="badge bg-success">Enable</span>';
                return '<strong>' . $user->name . '</strong><br>' . $disabled;
            })
            ->addColumn('avatar', function ($user) {
                return view('admin.users.components.avatar', compact('user'));
            })
            ->addColumn('action', function ($user) {
                return view('admin.users.components.actions', compact('user'));
            })
            ->addColumn('last_login', function ($user) {
                return $user->last_login_at ? $user->last_login_at : 'Belum Pernah';
            })
            ->addColumn('role', function ($user) {
                $penempatan = '';

                if ($user->role == 'Gudang' && $user->wirehouse) {
                    $penempatan = '<br>Penempatan : <strong>' . $user->wirehouse->name . '</strong>';
                } elseif ($user->role == 'Kasir' && $user->shop) {
                    $penempatan = '<br>Penempatan : <strong>' . $user->shop->name . '</strong>';
                }

                return '<span class="badge bg-label-primary">' . $user->role . '</span> ' . $penempatan;
            })



            ->rawColumns(['action', 'role', 'avatar', 'name', 'last_login'])
            ->make(true);
    }
    public function store(Request $request)
    {
        if ($request->filled('id')) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }


        if ($request->filled('id')) {
            $usersData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
                'is_disabled' => $request->input('is_disabled'),
                'id_shop' => $request->input('id_shop'),
                'id_wirehouse' => $request->input('id_wirehouse'),
            ];
            $user = User::find($request->input('id'));
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            if ($request->input('role') == 'Gudang') {
                $usersData['id_shop'] == null;
            } elseif ($request->input('role') == 'Kasir') {
                $usersData['id_wirehouse'] == null;
            }
            $user->update($usersData);
            $message = 'user updated successfully';
        } else {
            $usersData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
                'password' => Hash::make('password'),
                'id_shop' => $request->input('id_shop'),
                'id_wirehouse' => $request->input('id_wirehouse'),
            ];

            if ($request->input('role') == 'Gudang') {
                $usersData['id_shop'] == null;
            } elseif ($request->input('role') == 'Kasir') {
                $usersData['id_wirehouse'] == null;
            }
            User::create($usersData);
            $message = 'user created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function resetPassword(Request $request)
    {
        $user = User::find($request->input('id'));
        if ($user) {
            $user->password = Hash::make('password');
            $user->save();

            return response()->json(['message' => 'Berhasil Reset Password ke default']);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    public function edit($id)
    {
        $User = User::find($id);

        if (!$User) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($User);
    }
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Gagal menghapus karena user telah memiliki riwayat, disable akun jika ingin menonaktifkan'], 500);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan lainnya
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getAll()
    {
        $user = User::all();
        return response()->json($user);
    }
}
