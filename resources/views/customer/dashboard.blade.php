<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Weather Styles (FIXED UI) -->
    <style>
        body {
            background: linear-gradient(180deg, #eef2ff 0%, #f8fafc 50%, #deebff 100%);
        }

        .weather-container {
            background: linear-gradient(135deg, rgba(236, 253, 245, 0.96), #ffffff);
            padding: 32px;
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            max-width: 920px;
            margin: auto;
            border: 1px solid rgba(15, 118, 110, 0.14);
        }

        .weather-header {
            font-size: 30px;
            margin-bottom: 22px;
            text-align: center;
            font-weight: 800;
            color: #0f766e;
        }

        .weather-details {
            text-align: center;
            margin-bottom: 24px;
            max-width: 580px;
            margin-inline: auto;
        }

        .weather-details p {
            margin: 8px 0;
            font-size: 15px;
            color: #334155;
        }

        .temp {
            font-size: 56px;
            font-weight: 900;
            color: #0f766e;
        }

        .forecast {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
            gap: 16px;
        }

        .forecast-day {
            background: rgba(14, 165, 233, 0.08);
            padding: 16px;
            border-radius: 20px;
            text-align: center;
            font-size: 14px;
            transition: transform 0.2s ease, background-color 0.2s ease;
            border: 1px solid rgba(56, 189, 248, 0.18);
        }

        .forecast-day:hover {
            background: rgba(14, 165, 233, 0.16);
            transform: translateY(-3px);
        }

        .forecast-day span {
            display: block;
            margin: 4px 0;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-100 via-slate-50 to-cyan-50 text-slate-900 pt-20">

<!-- HEADER -->
<header class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-[95%] md:w-[90%] rounded-3xl backdrop-blur-xl bg-white/80 border border-slate-200 shadow-2xl">
    <div class="container mx-auto flex justify-between items-center px-6 py-3">

        <h1 class="text-2xl font-bold text-blue-600">
            Travel<span class="text-gray-800">Navigator</span>
        </h1>

        <nav class="hidden md:flex items-center space-x-6 text-slate-700">
            <a href="{{ route('home') }}" class="hover:text-teal-600 transition">Home</a>
            <a href="{{ route('guides.index') }}" class="hover:text-teal-600 transition">Book Guide</a>
            <a href="{{ route('currency.index') }}" class="hover:text-teal-600 transition">Currency</a>
            <a href="{{ route('blogs.index') }}" class="hover:text-teal-600 transition">Blogs</a>
            <a href="{{ route('home') }}#contact" class="hover:text-teal-600 transition">Contact</a>
        </nav>

        <div class="flex items-center space-x-4">
            <span class="text-gray-700 font-semibold">
                {{ auth()->user()->name }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

<!-- SEARCH -->
<section class="container mx-auto mt-32 text-center max-w-6xl">
    <h2 class="text-3xl md:text-4xl font-bold mb-8 text-slate-900">Find Your Perfect Room</h2>

    <form action="{{ route('dashboard') }}" method="GET"
          class="bg-white/95 border border-slate-200 p-6 rounded-3xl shadow-xl grid grid-cols-1 md:grid-cols-5 gap-4">

        <div>
            <label class="text-sm text-slate-500 font-medium">Budget</label>
            <div class="flex gap-3 mt-2">
                <input type="number" name="min_price" placeholder="Min"
                       value="{{ request('min_price') }}"
                       class="w-full p-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent">

                <input type="number" name="max_price" placeholder="Max"
                       value="{{ request('max_price') }}"
                       class="w-full p-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent">
            </div>
        </div>

        <div>
            <label class="text-sm text-slate-500 font-medium">Check In</label>
            <input type="date" name="checkin"
                   value="{{ request('checkin') }}"
                   class="w-full p-3 border border-slate-200 rounded-2xl mt-2 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent">
        </div>

        <div>
            <label class="text-sm text-slate-500 font-medium">Check Out</label>
            <input type="date" name="checkout"
                   value="{{ request('checkout') }}"
                   class="w-full p-3 border border-slate-200 rounded-2xl mt-2 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent">
        </div>

        <div>
            <label class="text-sm text-slate-500 font-medium">Guests</label>
            <select name="guests" class="w-full p-3 border border-slate-200 rounded-2xl mt-2 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent">
                <option value="">Any</option>
                <option value="1" {{ request('guests') == 1 ? 'selected' : '' }}>1 Guest</option>
                <option value="2" {{ request('guests') == 2 ? 'selected' : '' }}>2 Guests</option>
                <option value="3" {{ request('guests') == 3 ? 'selected' : '' }}>3 Guests</option>
                <option value="4" {{ request('guests') == 4 ? 'selected' : '' }}>4+ Guests</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit"
                    class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white py-3 rounded-full shadow-lg hover:from-teal-600 hover:to-cyan-600 transition">
                Search Rooms
            </button>
        </div>
    </form>
</section>

<!-- ROOMS -->
@if(isset($rooms))
<section class="container mx-auto mt-10">
    <h3 class="text-2xl font-bold text-center mb-6">Available Rooms</h3>

    @if($rooms->isEmpty())
        <p class="text-center text-gray-500">No rooms found.</p>
    @else
    <div class="grid md:grid-cols-3 gap-6">
        @foreach($rooms as $room)
        <div class="bg-white/95 rounded-3xl border border-slate-200 shadow-xl overflow-hidden transform transition hover:-translate-y-1 hover:shadow-2xl">

            @if($room->image)
                <img src="{{ asset('storage/' . $room->image) }}"
                     class="w-full h-44 object-cover rounded-t-3xl mb-3">
            @endif

            <div class="p-5">
                <h2 class="text-xl font-semibold text-slate-900">{{ $room->name }}</h2>
                <p class="text-teal-600 font-bold text-lg mt-1">৳ {{ number_format($room->price) }}</p>

                <p class="text-sm text-slate-500 mt-3 leading-6">
                    {{ \Illuminate\Support\Str::limit($room->description, 90) }}
                </p>

                <a href="/rooms/{{ $room->id }}"
                   class="block mt-6 text-center bg-gradient-to-r from-teal-500 to-cyan-500 text-white py-3 rounded-2xl shadow-lg hover:from-teal-600 hover:to-cyan-600 transition">
                    View Details
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>
@endif


<!-- PROFILE CARD -->
<section class="container mx-auto mt-10 px-4 max-w-6xl">
    <div class="bg-white/95 rounded-3xl border border-slate-200 shadow-2xl p-8 flex flex-col md:flex-row items-center gap-8 backdrop-blur-sm relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-teal-100 to-cyan-50 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/4"></div>

        <!-- Avatar -->
        <div class="relative z-10 w-32 h-32 rounded-full bg-gradient-to-tr from-teal-500 to-cyan-400 flex items-center justify-center text-white text-5xl font-extrabold shadow-lg border-4 border-white">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
        </div>

        <!-- User Info -->
        <div class="relative z-10 flex-1 text-center md:text-left">
            <h2 class="text-3xl font-bold text-slate-800 mb-1">
                {{ auth()->user()->name }}
            </h2>
            <p class="text-teal-600 font-medium mb-4">Travel Enthusiast</p>

            <div class="flex flex-col md:flex-row gap-4 md:gap-8 text-slate-600">
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                @if(auth()->user()->phone)
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>{{ auth()->user()->phone }}</span>
                </div>
                @endif
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Joined {{ auth()->user()->created_at->format('M Y') }}</span>
                </div>
            </div>
            
            @if(isset($booking))
            <div class="mt-6 bg-white/60 p-4 rounded-2xl border border-teal-100 inline-block shadow-sm backdrop-blur-sm">
                <p class="text-xs text-teal-600 mb-1 uppercase font-bold tracking-wider">Upcoming Stay Countdown</p>
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-teal-500 animate-pulse"></div>
                    <span id="profile-countdown" class="text-xl font-bold text-slate-800 font-mono tracking-tight">Calculating...</span>
                </div>
            </div>
            @else
            <p class="mt-6 text-sm text-slate-500 bg-slate-50 p-3 rounded-xl inline-block border border-slate-100">
                Welcome back 👋 Ready to explore new destinations?
            </p>
            @endif
        </div>
    </div>
</section>
<!-- DESTINATIONS -->
<section class="container mx-auto mt-10">
    <div class="flex justify-between items-center mb-6 px-2">
        <h3 class="text-2xl font-bold">Top Destinations</h3>

        <a href="{{ route('destinations.index') }}"
           class="text-blue-600 font-semibold hover:underline">
           See All →
        </a>
    </div>

    <div id="destinationContainer" class="grid md:grid-cols-4 gap-6"></div>
</section>

<!-- WEATHER -->
<section class="container mx-auto mt-12 px-4">
    <div class="weather-container">

        <div class="weather-header">
            Weather Review - Cox's Bazar
        </div>

        <div class="weather-details">
            <p><strong>Current Temperature:</strong> <span class="temp">Loading...</span></p>
            <p><strong>Humidity:</strong> <span id="humidity">Loading...</span></p>
            <p><strong>Wind:</strong> <span id="wind">Loading...</span></p>
            <p><strong>Precipitation:</strong> <span id="precipitation">Loading...</span></p>
        </div>

        <div class="forecast">
            <div class="forecast-day"><span>Saturday</span><span>Thunderstorms</span><span>26°C / 23°C</span></div>
            <div class="forecast-day"><span>Sunday</span><span>Thunderstorms</span><span>28°C / 24°C</span></div>
            <div class="forecast-day"><span>Monday</span><span>Cloudy</span><span>29°C / 24°C</span></div>
            <div class="forecast-day"><span>Tuesday</span><span>Partly Cloudy</span><span>31°C / 26°C</span></div>
            <div class="forecast-day"><span>Wednesday</span><span>Thunderstorms</span><span>32°C / 26°C</span></div>
        </div>

    </div>
</section>

<!-- JS -->
<script>
const container = document.getElementById('destinationContainer');

function loadDestinations() {
    fetch('/api/random-destinations')
        .then(res => res.json())
        .then(data => {
            container.innerHTML = '';

            data.forEach(dest => {
                const card = document.createElement('div');

                card.className = "bg-white/95 rounded-3xl border border-slate-200 shadow-xl overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition-transform duration-300 cursor-pointer";

                card.innerHTML = `
                    <img src="${dest.image ?? 'https://via.placeholder.com/300'}"
                         class="w-full h-44 object-cover">
                    <div class="p-4">
                        <h4 class="font-semibold text-lg text-slate-900">${dest.name}</h4>
                        <p class="text-sm text-slate-500 mt-1">${dest.category ?? ''}</p>
                        <p class="text-yellow-500 text-sm font-semibold mt-4">
                            ⭐ ${dest.rating ?? 0} / 5
                        </p>
                    </div>
                `;

                card.onclick = () => {
                    window.location.href = `/destinations/${dest.id}`;
                };

                container.appendChild(card);
            });
        });
}

loadDestinations();
setInterval(loadDestinations, 5000);

// WEATHER API
const apiKey = '79268fcd9f9011acd22a66eb1eea76d6';
const city = "Cox's Bazar";

const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
        document.querySelector('.temp').textContent = `${data.main.temp}°C`;
        document.getElementById('humidity').textContent = `${data.main.humidity}%`;
        document.getElementById('wind').textContent = `${data.wind.speed} km/h`;
        document.getElementById('precipitation').textContent = data.weather[0].description;
    })
    .catch(() => {
        document.querySelector('.temp').textContent = 'Error loading weather';
    });
</script>

</body>
</html>
@if(isset($booking))
<script>
    const endTime = new Date("{{ $booking->end_date }}").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance <= 0) {
            document.getElementById("profile-countdown").innerHTML = "Stay Completed";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("profile-countdown").innerHTML =
            `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
</script>
@endif

