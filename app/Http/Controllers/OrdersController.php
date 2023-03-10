<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrdersController extends Controller
{

    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        //->orWhere('email', 'LIKE', "%{$value}%")
                        ->orWhere('email', 'LIKE', "%{$value}%");
                });
            });
        });
        $orders = QueryBuilder::for(Orders::class)
            ->defaultSort('-id')
            ->allowedSorts(['id', 'email', 'date', 'phone', 'address'])
            ->allowedFilters(['id', 'email', 'date', 'phone', 'address', $globalSearch])
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('Orders/Index', ['orders' => $orders])->table(function (InertiaTable $table) {
            $table->column('id', 'ID', searchable: true, sortable: true);
            $table->column('date', 'Дата', searchable: true, sortable: true);
            $table->column('phone', 'Телефон', searchable: true, sortable: true);
            $table->column('email', 'E-mail', searchable: true, sortable: true);
            $table->column('address', 'Адрес', searchable: true, sortable: true);
            $table->column('summ', 'Сумма', searchable: true, sortable: true);
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render(
            'Orders/Create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'date' => 'required|integer',
            'email' => 'required|email',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'summ' => 'required|integer|max:255',
        ]);
        Orders::create([
            'date' => $request->date,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'summ' => $request->summ,
        ]);
        sleep(1);

        return redirect()->route('orders.index')->with('message', 'Заказ успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $order)
    {
        $products = $order->products;
        return Inertia::render(
            'Orders/Edit',
            [
                'order' => $order,
                'products' => Products::all()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orders $order): RedirectResponse
    {
        //
        $request->validate([
            'date' => 'required|integer',
            'email' => 'required|email',
            'phone' => ['string', 'regex:/^(\+7\s?|8)\d{3}\s?\d{3}\s?\d{2}\s?\d{2}$/'],
            'address' => 'string|max:255',
            'summ' => 'required|integer|min:3000',
        ]);

        $order->date = $request->date;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->address = $request->address;

        $a = [];
        $summ = 0;
        foreach ($request->products as $product) {
            $a[$product['id']] = ["qnt" => $product['pivot']['qnt']];
            $summ += $product['price'] * $product['pivot']['qnt'];
        }

        $order->products()->detach();

        $order->products()->attach($a);

        $order->summ = $summ;

        $order->save();

        sleep(1);


        //return Redirect::route('profile.edit');
        return redirect()->route('orders.index')->with('message', 'Заказ успешно изменён');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orders $order)
    {
        //
        $order->delete();
        sleep(1);

        return redirect()->route('orders.index')->with('message', 'Заказ успешно удалён');
    }
}
