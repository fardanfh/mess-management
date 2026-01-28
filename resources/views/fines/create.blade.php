@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Add Fine</h1>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                    <h3 class="text-red-800 font-semibold mb-2">An Error Occurred</h3>
                    <ul class="text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Checkin Info --}}
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                <h3 class="text-blue-800 font-semibold mb-3">Check-in Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Driver</p>
                        <p class="font-semibold text-gray-800">{{ $checkin->driver->nama_driver ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Room</p>
                        <p class="font-semibold text-gray-800">{{ $checkin->room->room_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Locker</p>
                        <p class="font-semibold text-gray-800">{{ $checkin->locker->locker_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Check-in Time</p>
                        <p class="font-semibold text-gray-800">{{ $checkin->check_in_time->format('d/m/Y H:i') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Fines Form --}}
            <form action="{{ route('fines.store') }}" method="POST">
                @csrf

                <input type="hidden" name="checkin_id" value="{{ $checkin->id }}">

                {{-- Fine Type Selection --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">Fine Type</label>
                    <div class="space-y-3">
                        @php
                            $fineTypes = [
                                'smoking' => ['label' => 'Smoking Fine', 'amount' => 50000],
                                'eating_drinking' => ['label' => 'Eating & Drinking on Bed Fine', 'amount' => 25000],
                                'drying_clothes' => ['label' => 'Drying Clothes in Mess Fine', 'amount' => 25000],
                                'littering' => ['label' => 'Littering in Mess Fine', 'amount' => 25000],
                            ];
                        @endphp

                        @foreach ($fineTypes as $type => $info)
                            <label class="flex items-center p-4 border border-gray-200 rounded cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="fine_type" value="{{ $type }}" 
                                    class="h-4 w-4 text-blue-600" required>
                                <span class="ml-3 flex-1">
                                    <span class="font-semibold text-gray-800">{{ $info['label'] }}</span>
                                    <span class="text-gray-600 ml-2">- Rp {{ number_format($info['amount'], 0, ',', '.') }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('fine_type')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">Notes (Optional)</label>
                    <textarea id="description" name="description" rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter fine notes..."></textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Add Fine
                    </button>
                    <a href="{{ route('checkins.show', $checkin->id) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
