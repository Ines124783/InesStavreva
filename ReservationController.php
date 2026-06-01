<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Guest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Показва списък с всички резервации
     */
    public function index()
    {
        $reservations = Reservation::with(['guest', 'room'])
            ->orderBy('check_in')
            ->paginate(15);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Показва формата за нова резервация
     */
    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        $guests = Guest::orderBy('last_name')->get();

        return view('reservations.create', compact('rooms', 'guests'));
    }

    /**
     * Записва нова резервация в базата данни
     */
    public function store(Request $request)
    {
        $request->validate([
            'guest_id'    => 'required|exists:guests,id',
            'room_id'     => 'required|exists:rooms,id',
            'check_in'    => 'required|date|after:today',
            'check_out'   => 'required|date|after:check_in',
        ]);

        $request->merge(['user_id' => auth()->user()->id]);

        Reservation::create($request->all());

        return redirect()->route('reservations.index')
            ->with('success', 'Резервацията е създадена успешно.');
    }

    /**
     * Показва детайли за конкретна резервация
     */
    public function show($id)
    {
        $reservation = Reservation::with(['guest', 'room'])->findOrFail($id);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Показва формата за редактиране
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (auth()->user()->isAdmin != 1 &&
            auth()->user()->id != $reservation->user_id) {
            return redirect()->route('reservations.index')
                ->with('error', 'Нямате права за тази операция.');
        }

        $rooms = Room::all();
        $guests = Guest::orderBy('last_name')->get();

        return view('reservations.edit', compact('reservation', 'rooms', 'guests'));
    }

    /**
     * Обновява съществуваща резервация
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if (auth()->user()->isAdmin != 1 &&
            auth()->user()->id != $reservation->user_id) {
            return redirect()->route('reservations.index')
                ->with('error', 'Нямате права за тази операция.');
        }

        $request->validate([
            'check_in'  => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'status'    => 'required|string',
        ]);

        $reservation->update($request->all());

        return redirect()->route('reservations.index')
            ->with('success', 'Резервацията е обновена успешно.');
    }

    /**
     * Изтрива резервация
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (auth()->user()->isAdmin != 1 &&
            auth()->user()->id != $reservation->user_id) {
            return redirect()->route('reservations.index')
                ->with('error', 'Нямате права за тази операция.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Резервацията е изтрита успешно.');
    }
}
