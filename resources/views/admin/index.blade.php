<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800">

<!-- Header -->
<header class="bg-slate-900 text-white sticky top-0 z-50 shadow-md">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Admin<span class="text-emerald-400">Panel</span></h2>
        <div class="flex items-center space-x-6">
            <a href="/admin/guides" class="text-sm font-medium hover:text-emerald-400 transition">Manage All Guides</a>
            <a href="/admin/logout" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-full text-sm font-semibold transition">Logout</a>
        </div>
    </div>
</header>

<main class="container mx-auto px-6 py-10 max-w-6xl">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        <!-- ROOM REQUESTS COLUMN -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">{{ count($requests) }}</span>
                    Room Requests
                </h3>
            </div>
            
            <div class="space-y-4">
                @forelse($requests as $room)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="text-lg font-bold text-slate-900">{{ $room->name }}</h4>
                                <p class="text-emerald-600 font-semibold">৳ {{ number_format($room->price) }} / night</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-bold uppercase rounded-full 
                                {{ $room->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($room->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $room->status }}
                            </span>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <a href="/admin/room/{{ $room->id }}" class="flex-1 bg-slate-100 text-slate-700 text-center py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition">View Details</a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 border-dashed text-center">
                        <p class="text-slate-500">No pending room requests.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- GUIDE REGISTRATION REQUESTS COLUMN -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <span class="bg-purple-100 text-purple-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">{{ count($guideRequests) }}</span>
                    Guide Registrations
                </h3>
            </div>
            
            <div class="space-y-4">
                @forelse($guideRequests as $guide)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition">
                        <div class="flex items-start gap-4 mb-4">
                            @if($guide->photo)
                                <img src="{{ asset($guide->photo) }}" class="w-16 h-16 rounded-full object-cover shadow-sm" alt="{{ $guide->name }}">
                            @else
                                <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-400 text-2xl font-bold shadow-sm">
                                    {{ substr($guide->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-slate-900">{{ $guide->name }}</h4>
                                <p class="text-sm text-slate-500">{{ $guide->email }}</p>
                                <p class="text-sm font-medium text-purple-600 mt-1">{{ $guide->specialization }} · {{ $guide->experience_years }} yrs exp</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="/admin/guides/{{ $guide->id }}" class="flex-1 bg-slate-100 text-slate-700 text-center py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition">View Details</a>
                            <a href="/admin/guides/{{ $guide->id }}/approve" class="flex-1 bg-emerald-500 text-white text-center py-2 rounded-lg text-sm font-semibold hover:bg-emerald-600 transition">Approve</a>
                            <a href="/admin/guides/{{ $guide->id }}/reject" class="flex-1 bg-red-500 text-white text-center py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">Decline</a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 border-dashed text-center">
                        <p class="text-slate-500">No pending guide registrations.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>
</main>

</body>
</html>