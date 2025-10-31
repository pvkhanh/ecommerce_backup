<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // =================== INDEX ===================
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if (!is_null($request->status) && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 📧 Lọc theo trạng thái email (đây là phần bạn đang thiếu)
        if ($request->has('verified') && $request->verified !== '') {
            if ($request->verified == '1') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verified == '0') {
                $query->whereNull('email_verified_at');
            }
        }


        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    // =================== CREATE ===================
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,buyer',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'bio' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'email_verified_at' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['remember_token'] = Str::random(60);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $this->userRepository->create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo người dùng thành công!');
    }

    // =================== EDIT & UPDATE ===================
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user)
            abort(404);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->userRepository->find($id);
        if (!$user)
            abort(404);

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,buyer',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'bio' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'email_verified_at' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $this->userRepository->update($id, $validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    // =================== SHOW ===================
    public function show($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user)
            abort(404);
        return view('admin.users.show', compact('user'));
    }

    // =================== SOFT DELETE ===================
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user)
            return redirect()->route('admin.users.index')->with('error', 'Người dùng không tồn tại!');
        if (auth()->check() && $user->id === auth()->id())
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể xóa chính mình!');

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Người dùng '{$user->username}' đã được chuyển vào thùng rác!");
    }

    // =================== TRASH ===================
    public function trashed(Request $request)
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return response()->json(['message' => 'Khôi phục người dùng thành công!']);
    }

    public function restoreAll()
    {
        User::onlyTrashed()->restore();
        return response()->json(['message' => 'Khôi phục tất cả người dùng thành công!']);
    }

    public function forceDelete($id)
    {
        User::onlyTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['message' => 'Xóa vĩnh viễn người dùng thành công!']);
    }

    public function forceDeleteSelected(Request $request)
    {
        $ids = $request->ids ?? [];
        if (count($ids)) {
            User::onlyTrashed()->whereIn('id', $ids)->forceDelete();
        }
        return response()->json(['message' => 'Xóa vĩnh viễn các người dùng đã chọn thành công!']);
    }


    // =================== TOGGLE STATUS ===================
    public function toggleStatus($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->is_active = $request->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!'
        ]);
    }
}