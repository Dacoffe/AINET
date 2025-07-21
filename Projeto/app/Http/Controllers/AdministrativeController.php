<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AdministrativeFormRequest;
use App\Models\Product;
use App\Models\Settings_shipping_costs;

class AdministrativeController extends Controller
{
    use \App\Traits\UserPhotoFileStorage;

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;

        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $stockAvailable = $query->paginate($perPage)->appends([
            'search' => $search,
        ]);

        // Estatísticas dos tipos de membros
        $numberOfBoardMembers = \App\Models\User::where('type', 'board')->count();
        $numberOfEmployees = \App\Models\User::where('type', 'employee')->count();
        $numberOfMembers = \App\Models\User::where('type', 'member')->count();

        // Outros dados
        $shippingCosts = \App\Models\Order::sum('shipping_cost') ?? 0;

        // Estatísticas de pedidos mensais (garantindo que sempre retorne uma coleção de valores)
        $monthlyOrderStats = $this->getMonthlyOrderStats()->values();


        return view('admin.dashboard', compact(
            'stockAvailable',
            'search',
            'numberOfBoardMembers',
            'numberOfEmployees',
            'numberOfMembers',
            'shippingCosts',
            'monthlyOrderStats',
            'currentYear'
        ));
    }


    private function getMonthlyOrderStats()
    {
        $currentYear = date('Y');
        $monthlyOrders = \App\Models\Order::selectRaw('
        MONTH(created_at) as month,
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = "canceled" THEN 1 ELSE 0 END) as canceled
    ')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $allMonths = collect(range(1, 12))->map(function ($month) use ($monthlyOrders) {
            $data = $monthlyOrders->get($month);
            return [
                'month' => $month,
                'completed' => $data->completed ?? 0,
                'pending' => $data->pending ?? 0,
                'canceled' => $data->canceled ?? 0,
                'total_orders' => $data->total_orders ?? 0,
            ];
        });

        return $allMonths->values();
    }

    public function create(): View
    {
        $newAdministrative = new User();
        $newAdministrative->type = 'board';
        return view('admin.create')
            ->with('administrative', $newAdministrative);
    }

    public function store(AdministrativeFormRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $newAdministrative = new User();
        $newAdministrative->type = 'board';
        $newAdministrative->name = $validatedData['name'];
        $newAdministrative->email = $validatedData['email'];
        $newAdministrative->gender = $validatedData['gender'];
        // Initial password is always 123
        $newAdministrative->password = bcrypt('123');
        $newAdministrative->save();
        // File store is the last thing to execute!
        // Files do not rollback, so the probability of having a pending file
        // (not referenced by any user) is reduced by being the last operation
        $this->storeUserPhoto($request->photo, $newAdministrative);
        $url = route('administratives.show', ['administrative' => $newAdministrative]);
        $htmlMessage = "Administrative <a href='$url'><u>{$newAdministrative->name}</u></a> has been created successfully!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(User $administrative): View
    {
        return view('admin.edit')
            ->with('administrative', $administrative);
    }

    public function update(AdministrativeFormRequest $request, User $administrative): RedirectResponse
    {
        $validatedData = $request->validated();
        $administrative->type = 'board';
        $administrative->name = $validatedData['name'];
        $administrative->email = $validatedData['email'];
        $administrative->gender = $validatedData['gender'];
        $administrative->save();
        if ($request->photo) {
            $this->deleteUserPhoto($administrative);
            $this->storeUserPhoto($request->photo, $administrative);
        }
        $url = route('admin.show', ['administrative' => $administrative]);
        $htmlMessage = "Administrative <a href='$url'><u>{$administrative->name}</u></a> has been updated successfully!";
        return redirect()->route('admin.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(User $administrative): RedirectResponse
    {
        try {
            $url = route('admin.show', ['administrative' => $administrative]);
            $fileName = $administrative->photo;
            $administrative->delete();
            $this->deletePhotoFile($fileName);
            $alertType = 'success';
            $alertMsg = "Administrative {$administrative->name} has been deleted successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the administrative
                            <a href='$url'><u>{$administrative->name}</u></a>
                            because there was an error with the operation!";
        }
        return redirect()->route('admin.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyPhoto(User $administrative): RedirectResponse
    {
        if ($this->deleteUserPhoto($administrative)) {
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of administrative {$administrative->name} has been deleted.");
        } else {
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "Photo of administrative {$administrative->name} does not exist.");
        }
    }

    public function show(User $administrative): View
    {
        return view('administratives.show')->with('administrative', $administrative);
    }

}
