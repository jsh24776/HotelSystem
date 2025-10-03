<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Display all inventory items
    public function index()
    {
        $items = Inventory::all();
        return view('admin.inventory.index', compact('items'));
    }

    // Show form to create a new inventory item
    public function create()
    {
        return view('admin.inventory.create');
    }

    // Store new inventory item
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        Inventory::create($request->all());

        return redirect()->route('admin.inventory.index')->with('success', 'Item added successfully.');
    }

    // Show a single inventory item
    public function show(Inventory $inventory)
    {
        return view('admin.inventory.show', compact('inventory'));
    }

    // Show form to edit an inventory item
    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    // Update an inventory item
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $inventory->update($request->all());

        return redirect()->route('admin.inventory.index')->with('success', 'Item updated successfully.');
    }

    // Delete an inventory item
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Item deleted successfully.');
    }
}
