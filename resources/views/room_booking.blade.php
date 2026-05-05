<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room — {{ $room->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 pt-16">

<div class="max-w-3xl mx-auto p-4">
    <a href="/rooms/{{ $room->id }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 mb-6">
        ← Back to Room
    </a>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-800 p-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Book Your Stay</h1>
            <p class="text-slate-300">{{ $room->name }} · ৳ {{ number_format($room->price) }} / night</p>
        </div>

        <div class="p-8">
            <form action="{{ route('room.checkout') }}" method="POST" id="bookingForm" class="space-y-6">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check-in Date *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                               min="{{ date('Y-m-d') }}" required
                               onchange="calculateCost()"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check-out Date *</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                               onchange="calculateCost()"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>
                </div>

                <!-- Cost Preview -->
                <div id="costPreview" class="hidden rounded-2xl bg-slate-50 border border-slate-200 p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800 border-b border-slate-200 pb-2">Booking Summary</h3>
                    
                    <div class="flex justify-between text-slate-600">
                        <span>Price per night</span>
                        <span class="font-medium text-slate-800">৳ {{ number_format($room->price) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Number of nights</span>
                        <span class="font-medium text-slate-800" id="previewDays">—</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                        <span class="text-base font-bold text-slate-800">Total Price</span>
                        <span class="text-2xl font-bold text-emerald-600" id="previewTotal">৳ 0</span>
                    </div>
                </div>

                <button type="submit" id="submitBtn" disabled
                        class="w-full flex justify-center items-center py-4 rounded-full bg-slate-800 text-white font-bold text-lg hover:bg-slate-900 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Proceed to Payment →
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const roomPrice = {{ $room->price }};

    function calculateCost() {
        const startDate = document.getElementById('start_date').value;
        const endDate   = document.getElementById('end_date').value;
        const preview   = document.getElementById('costPreview');
        const submitBtn = document.getElementById('submitBtn');

        if (!startDate || !endDate) {
            preview.classList.add('hidden');
            submitBtn.disabled = true;
            return;
        }

        const start = new Date(startDate);
        const end   = new Date(endDate);
        const days  = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

        if (days < 1) {
            preview.classList.add('hidden');
            submitBtn.disabled = true;
            return;
        }

        const total = roomPrice * days;

        document.getElementById('previewDays').textContent = days + ' night' + (days > 1 ? 's' : '');
        document.getElementById('previewTotal').textContent = '৳ ' + total.toLocaleString();

        preview.classList.remove('hidden');
        submitBtn.disabled = false;
    }
</script>

</body>
</html>
