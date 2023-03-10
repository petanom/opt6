<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Collection;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductsController extends Controller
{

    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        //->orWhere('email', 'LIKE', "%{$value}%")
                        ->orWhere('name', 'LIKE', "%{$value}%");
                });
            });
        });
        $products = QueryBuilder::for(Products::class)
            ->defaultSort('-id')
            ->allowedSorts(['id', 'name', 'price'])
            ->allowedFilters(['id', 'name', 'price', $globalSearch])
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('Products/Index', ['products' => $products])->table(function (InertiaTable $table) {
            $table->column('id', 'ID', searchable: true, sortable: true);
            $table->column('name', 'Название', searchable: true, sortable: true);
            $table->column('price', 'Цена', searchable: true, sortable: true);
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        //
        return Inertia::render(
            'Products/Create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|integer',
        ]);
        Products::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);
        sleep(1);

        return redirect()->route('products.index')->with('message', 'Товар успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $product)
    {
        //
        return Inertia::render(
            'Products/Edit',
            [
                'product' => $product
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        //
        $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|integer',
        ]);

        $product->name = $request->name;
        $product->price = $request->price;



        $product->save();

        sleep(1);


        //return Redirect::route('profile.edit');
        return redirect()->route('products.index')->with('message', 'Товар успешно изменён');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $product)
    {
        //
        $product->delete();
        sleep(1);

        return redirect()->route('products.index')->with('message', 'Товар успешно удалён');
    }
}
