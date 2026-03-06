<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $filters = [
      ...$request->only(['role'])
    ];

    $users = User::query()
      ->latest()
      ->filter($filters)
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('Admin/User/Index', [
      'users' => $users,
      'filters' => $filters,
      'roles' => User::select('role')->distinct()->pluck('role'),
    ]);
  }

  public function toggleStatus(User $user)
  {
    if ($user && $user->role == 'admin') {
      return back()->with('error', 'Admin account cannot be deleted.');
    }

    $user->is_active = !$user->is_active;
    $user->save();

    return back()->with('success', 'User status updated.');
  }
}
