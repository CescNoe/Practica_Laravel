<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Model_Brand;
use App\Models\Brand;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $models = Model_Brand::select(
            'model_brands.id',
            'model_brands.name',
            'b.name as brand',
            'model_brands.description',
            'model_brands.created_at',
            'model_brands.updated_at'
        )->join('brands as b', 'model_brands.brand_id', '=', 'b.id');

        if ($request->ajax()) {
            return DataTables::of($models)
                ->addColumn("edit", function ($model) {
                    return ' <button class="btn btn-warning btn-sm btnEditar" id= ' . $model->id . '><i
                                        class="fas fa-pen"></i></button>';
                })
                ->addColumn("delete", function ($model) {
                    return '<form action="' . route('admin.models.destroy', $model) . '" method="POST" class="frmDelete">
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
            return view('admin.models.index', compact('models'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all()->pluck('name', 'id');
        return view('admin.models.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'brand_id' => 'required|exists:brands,id',
                'description' => 'nullable|string',
            ]);

            Model_Brand::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
            ]);

            return response()->json(['success' => 'Modelo registrado con éxito.'], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Error al registrar el modelo.'], 500);
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
        $model = Model_Brand::find($id);
        $brands = Brand::all()->pluck('name', 'id');
        return view('admin.models.edit', compact('model', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $model = Model_Brand::find($id);
            $request->validate([
                'name' => 'required|string|max:255',
                'brand_id' => 'required|exists:brands,id',
                'description' => 'nullable|string',
            ]);
            
            $model->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
            ]);

            return response()->json(['success' => 'Modelo actualizado con éxito.'], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Error al actualizar el modelo.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $model = Model_Brand::find($id);
            $model->delete();
            return redirect()->route('admin.models.index')->with('action', 'Modelo eliminado correctamente.');
        } catch (Throwable $e) {
            return redirect()->route('admin.models.index')->with('error', 'Error al eliminar el modelo.');
        }
    }
}
