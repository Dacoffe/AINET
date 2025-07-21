<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileFormRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{

    protected $pagination = 25;
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'name');
        $order = $request->get('order', 'asc');
        $search = $request->get('search');

        $allowedSorts = ['name', 'balance', 'type', 'nif'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $users = User::select('users.*')
            ->leftJoin('cards', 'users.id', '=', 'cards.id');

        // Adicionar busca
        if ($search) {
            $users->where('users.name', 'like', '%' . $search . '%');
        }

        if ($sortBy === 'balance') {
            $users = $users->orderBy('cards.balance', $order);
        } else {
            $users = $users->orderBy("users.$sortBy", $order);
        }

        $users = $users->with('card')->paginate($this->pagination);

        return view('profile.index', compact('users'));
    }

    public function show(User $user = null)
    {
        $authUser = Auth::user();

        // Se não for passado um user, mostra o próprio perfil
        if (!$user) {
            $user = $authUser;
        }

        $card = Card::find($user->id);
        $cardBalance = $card ? $card->balance : 0;
        $types = ['member', 'board', 'employee'];

        return view('profile.my_profile', compact('user', 'cardBalance', 'types'));
    }


    public function update(ProfileFormRequest $request, User $user = null)
    {
        $authUser = Auth::user();

        // Se não for passado um user, assume o próprio utilizador autenticado
        if (!$user) {
            $user = $authUser;
        }

        // Bloqueia membros que tentam editar outro perfil
        if ($authUser->type === 'member' && $authUser->id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validated();

        // Upload de nova foto
        if ($request->hasFile('photo')) {
            if ($user->photo && $user->photo !== 'anonymous.png' && Storage::disk('public')->exists("users/{$user->photo}")) {
                Storage::disk('public')->delete("users/{$user->photo}");
            }
            $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('users', $filename, 'public');
            $validated['photo'] = $filename;
        }

        // Verifica se houve mudanças
        $changed = false;
        foreach ($validated as $key => $value) {
            if ($user->$key != $value) {
                $changed = true;
                break;
            }
        }

        if (!$changed) {
            return redirect()->back()->with('info', 'Nenhuma alteração foi feita.');
        }

        $user->update($validated);

        // Decide o redirecionamento com base em quem está a editar
        if ($authUser->id === $user->id) {
            // Atualizou o seu próprio perfil
            return redirect()->route('my-profile.show', $user->id)->with('success', 'Profile updated successfully!');
            $user->update($validated);
        } else {
            // Atualizou o perfil de outro utilizador
            return redirect()->route('profiles.show', $user->id)->with('success', 'Profile updated successfully!');
        }
    }

    public function block($id)
    {
        $user = User::findOrFail($id);

        // Verificar se o usuário atual tem permissão para bloquear
        if (Auth::user()->type !== 'board') {
            abort(403, 'Unauthorized action.');
        }

        $user->update(['blocked' => 1]);

        return redirect()->back()->with('success', 'Utilizador bloqueado com sucesso!');
    }

    public function unblock($id)
    {
        $user = User::findOrFail($id);

        // Verificar se o usuário atual tem permissão para desbloquear
        if (Auth::user()->type !== 'board') {
            abort(403, 'Unauthorized action.');
        }

        $user->update(['blocked' => 0]);

        return redirect()->back()->with('success', 'Utilizador desbloqueado com sucesso!');
    }

    public function destroy(User $user)
    {
        $userName = $user->name;

        try {
            // Soft delete (preenche deleted_at)
            $user->delete();

            $alertType = 'success';
            $alertMsg = "User <u>{$userName}</u> has been deleted successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the user <u>{$userName}</u> because there was an error with the operation!";
        }

        return redirect()->route('profiles.show')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
