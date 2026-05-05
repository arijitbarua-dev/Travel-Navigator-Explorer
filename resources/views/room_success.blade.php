<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 text-center border border-slate-100">
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-800 mb-2">Payment Successful!</h1>
        <p class="text-slate-500 mb-8">Thank you for your booking. Your room has been successfully reserved.</p>

        <div class="bg-slate-50 rounded-2xl p-5 mb-8 text-left space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Room</span>
                <span class="font-medium text-slate-800">{{ $metadata->room_name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Total Paid</span>
                <span class="font-bold text-emerald-600">৳ {{ number_format($metadata->price) }}</span>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('room.receipt') }}?session_id={{ $session->id }}" class="block w-full py-3 rounded-full bg-blue-600 text-white font-semibold text-center hover:bg-blue-700 transition">📄 View Receipt</a>
            <a href="/dashboard" class="block w-full py-3 rounded-full bg-slate-100 text-slate-700 font-semibold text-center hover:bg-slate-200 transition">Return to Dashboard</a>
        </div>
    </div>
</body>
</html>
