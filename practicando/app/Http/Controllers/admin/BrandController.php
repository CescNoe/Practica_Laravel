<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::all();
        if ($request->ajax()) {
            return DataTables::of($brands)
                ->addColumn("logo", function ($brand) {
                    return '<img src="' . ($brand->logo == '' ? asset('storage/brand_logo/no-image-icon-6.png') : asset($brand->logo)) . '" width="50" height="50">';
                })
                ->addColumn("edit", function ($brand) {
                    return ' <button class="btn btn-warning btn-sm btnEditar" id= ' . $brand->id . '><i
                                        class="fas fa-pen"></i></button>';
                })
                ->addColumn("delete", function ($brand) {
                    return '<form action="' . route('admin.brands.destroy', $brand) . '" method="POST" class="frmDelete">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>';
                })
                ->rawColumns(['logo', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.brands.index', compact('brands'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $logo = "";
            $request->validate([
                'name' => 'required|unique:brands|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($request->logo != "") {
                $image = $request->file('logo')->store("public/brand_logo");
                $logo = Storage::url($image);
            }
            Brand::create([
                "name" => $request->name,
                "logo" => $logo,
                "description" => $request->description,
            ]);
            return response()->json(['success' => 'Marca registrada con éxito.'], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Error al registrar la marca.'], 500);
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
        $brand = Brand::find($id);
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $brand = Brand::find($id);
            $logo = "";
            $request->validate([
                'name' => 'unique:brands,name,' . $brand->id . '|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'nullable|string',
            ]);
            if ($request->logo != "") {
                $image = $request->file('logo')->store("public/brand_logo");
                $logo = Storage::url($image);
                $brand->update([
                    "name" => $request->name,
                    "logo" => $logo,
                    "description" => $request->description,
                ]);
            } else {
                $brand->update([
                    "name" => $request->name,
                    "description" => $request->description,
                ]);
            }
            return response()->json(['success' => 'Marca actualizada con éxito.'], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Error al actualizar la marca.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Marca eliminada con éxito.');
    }
}
