<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vehicles = Vehicle::all();
        if ($request->ajax()) {
            return DataTables::of($vehicles)
                ->addColumn("edit", function ($vehicle) {
                    return ' <button class="btn btn-warning btn-sm btnEditar" id= ' . $vehicle->id . '><i
                                        class="fas fa-pen"></i></button>';
                })
                ->addColumn("delete", function ($vehicle) {
                    return '<form action="' . route('admin.vehicles.destroy', $vehicle) . '" method="POST" class="frmDelete">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.vehicles.index', compact('vehicles'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:vehicles|string|max:255',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'description' => 'nullable|string',
            ]);
            Vehicle::create([
                'name' => $request->name,
                'brand' => $request->brand,
                'model' => $request->model,
                'year' => $request->year,
                'description' => $request->description,
            ]);
            return response()->json(['success' => 'Vehículo registrado con éxito.'], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Error al registrar el vehículo.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::find($id);
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $vehicle = Vehicle::find($id);
            $request->validate([
                'name' => 'unique:vehicles,name,' . $vehicle->id . '|string|max:255',
                'brand' => 'string|max:255',
                'model' => 'string|max:255',
                'year' => 'integer|min:1900|max:' . (date('Y') + 1),
                'description' => 'nullable|string',
            ]);
            $vehicle->update([
                'name' => $request->name,
                'brand' => $request->brand,
                'model' => $request->model,
                'year' => $request->year,
                'description' => $request->description,
            ]);
            return response()->json(['success' => 'Vehículo actualizado con éxito.'], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Error al actualizar el vehículo.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::find($id);
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('action', 'Vehículo eliminado correctamente.');
    }
}
