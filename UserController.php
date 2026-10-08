<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // パスワード暗号化に必須のクラス

class UserController extends Controller
{
    public function index()
    {
        // 全件取得から、最新順20件ずつのページネーションに変更！
        $users = User::latest()->paginate(20);
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        // 不正なデータを通さないための必須入力・形式チェックを追加！
        $validated = $request->validate([
            'name'     => 'required|max:50',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        // 安全な User::create を使い、かつパスワードを Hash::make で暗号化して保存！
        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/users');
    }
}
