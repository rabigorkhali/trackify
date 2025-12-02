<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = TicketStatus::orderBy('order')->get();
        return view('backend.system.ticket-status.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.system.ticket-status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get the maximum order value and add 1
        $maxOrder = TicketStatus::max('order') ?? 0;

        TicketStatus::create([
            'name' => $request->name,
            'color' => $request->color,
            'status' => $request->status,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('ticket-statuses.index')
            ->with('success', 'Ticket status created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketStatus $ticketStatus)
    {
        return view('backend.system.ticket-status.show', compact('ticketStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketStatus $ticketStatus)
    {
        return view('backend.system.ticket-status.edit', compact('ticketStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketStatus $ticketStatus)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ticketStatus->update([
            'name' => $request->name,
            'color' => $request->color,
            'status' => $request->status,
        ]);

        return redirect()->route('ticket-statuses.index')
            ->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketStatus $ticketStatus)
    {
        // Check if there are any tickets using this status
        if ($ticketStatus->tickets()->count() > 0) {
            return redirect()->route('ticket-statuses.index')
                ->with('error', 'Cannot delete status. There are tickets using this status.');
        }

        $ticketStatus->delete();

        return redirect()->route('ticket-statuses.index')
            ->with('success', 'Ticket status deleted successfully.');
    }

    /**
     * Update the order of statuses via drag and drop
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:ticket_statuses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 422);
        }

        foreach ($request->orders as $order => $id) {
            TicketStatus::where('id', $id)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }
}

