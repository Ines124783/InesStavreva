@extends('layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-calendar-alt me-2"></i>Управление на резервации</h2>
    <a href="{{ route('reservations.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Нова резервация
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Гост</th>
                    <th>Стая</th>
                    <th>Настаняване</th>
                    <th>Напускане</th>
                    <th>Обща сума</th>
                    <th>Статус</th>
                    <th colspan="2">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>
                        {{ $reservation->guest->first_name }}
                        {{ $reservation->guest->last_name }}
                    </td>
                    <td>Стая {{ $reservation->room->room_number }}</td>
                    <td>{{ $reservation->check_in }}</td>
                    <td>{{ $reservation->check_out }}</td>
                    <td>{{ number_format($reservation->total_price, 2) }} лв.</td>
                    <td>
                        @php
                            $statusColors = [
                                'pending'    => 'warning',
                                'confirmed'  => 'success',
                                'checked-in' => 'primary',
                                'checked-out'=> 'secondary',
                                'cancelled'  => 'danger',
                            ];
                            $color = $statusColors[$reservation->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </td>
                    <td>
                        @if(Auth::user()->isAdmin == 1 ||
                            Auth::user()->id == $reservation->user_id)
                            <a href="{{ route('reservations.edit', $reservation->id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                    </td>
                    <td>
                        @if(Auth::user()->isAdmin == 1 ||
                            Auth::user()->id == $reservation->user_id)
                            <form action="{{ route('reservations.destroy', $reservation->id) }}"
                                  method="post"
                                  onsubmit="return confirm('Да се изтрие ли резервацията?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        Няма намерени резервации.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Страниране --}}
<div class="mt-3">
    {{ $reservations->links() }}
</div>

@endsection
