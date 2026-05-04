<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Weather Styles (FIXED UI) -->
    <style>
        .weather-container {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            max-width: 900px;
            margin: auto;
        }

        .weather-header {
            font-size: 26px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .weather-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .weather-details p {
            margin: 6px 0;
            font-size: 15px;
        }

        .temp {
            font-size: 56px;
            font-weight: bold;
            color: #0f766e;
        }

        .forecast {
            margin-top: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
        }

        .forecast-day {
            background: #f1f5f9;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: 13px;
            transition: 0.2s;
        }

        .forecast-day:hover {
            background: #e0f2fe;
            transform: translateY(-2px);
        }

        .forecast-day span {
            display: block;
            margin: 2px 0;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 pt-20">

<!-- HEADER -->
<header class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-[95%] md:w-[90%] rounded-3xl backdrop-blur-xl bg-white/30 border border-white/20 shadow-2xl">
    <div class="container mx-auto flex justify-between items-center px-6 py-3">

        <h1 class="text-2xl font-bold text-blue-600">
            Travel<span class="text-gray-800">Navigator</span>
        </h1>

        <nav class="hidden md:flex items-center space-x-8 text-gray-700">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('destinations.index') }}">Destinations</a>
            <a href="{{ route('booking.form') }}">Book Guide</a>
            <a href="{{ route('currency.index') }}">Currency</a>
            <a href="{{ route('blogs.index') }}">Blogs</a>
            <a href="{{ route('home') }}#contact">Contact</a>
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
<section class="container mx-auto mt-32 text-center">
    <h2 class="text-3xl font-bold mb-8">Find Your Perfect Room</h2>

    <form action="{{ route('dashboard') }}" method="GET"
          class="bg-white p-6 rounded-2xl shadow-lg grid grid-cols-1 md:grid-cols-5 gap-4">

        <div>
            <label class="text-sm text-gray-600">Budget</label>
            <div class="flex gap-2 mt-1">
                <input type="number" name="min_price" placeholder="Min"
                       value="{{ request('min_price') }}"
                       class="w-full p-2 border rounded">

                <input type="number" name="max_price" placeholder="Max"
                       value="{{ request('max_price') }}"
                       class="w-full p-2 border rounded">
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-600">Check In</label>
            <input type="date" name="checkin"
                   value="{{ request('checkin') }}"
                   class="w-full p-2 border rounded mt-1">
        </div>

        <div>
            <label class="text-sm text-gray-600">Check Out</label>
            <input type="date" name="checkout"
                   value="{{ request('checkout') }}"
                   class="w-full p-2 border rounded mt-1">
        </div>

        <div>
            <label class="text-sm text-gray-600">Guests</label>
            <select name="guests" class="w-full p-2 border rounded mt-1">
                <option value="">Any</option>
                <option value="1" {{ request('guests') == 1 ? 'selected' : '' }}>1 Guest</option>
                <option value="2" {{ request('guests') == 2 ? 'selected' : '' }}>2 Guests</option>
                <option value="3" {{ request('guests') == 3 ? 'selected' : '' }}>3 Guests</option>
                <option value="4" {{ request('guests') == 4 ? 'selected' : '' }}>4+ Guests</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit"
                    class="w-full bg-teal-500 text-white py-2 rounded-full hover:bg-teal-600">
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
        <div class="bg-white rounded-xl shadow p-4">

            @if($room->image)
                <img src="{{ asset('storage/' . $room->image) }}"
                     class="w-full h-40 object-cover rounded mb-3">
            @endif

            <h2 class="text-lg font-semibold">{{ $room->name }}</h2>
            <p class="text-teal-600 font-bold">৳ {{ number_format($room->price) }}</p>

            <p class="text-sm text-gray-500 mt-2">
                {{ \Illuminate\Support\Str::limit($room->description, 80) }}
            </p>

            <a href="/rooms/{{ $room->id }}"
               class="block mt-4 text-center bg-teal-500 text-white py-2 rounded hover:bg-teal-600">
                View Details
            </a>
        </div>
        @endforeach
    </div>
    @endif
</section>
@endif

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

                card.className = "bg-white rounded-xl shadow overflow-hidden hover:scale-105 transition cursor-pointer";

                card.innerHTML = `
                    <img src="${dest.image ?? 'https://via.placeholder.com/300'}"
                         class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h4 class="font-semibold text-lg">${dest.name}</h4>
                        <p class="text-sm text-gray-500">${dest.category ?? ''}</p>
                        <p class="text-yellow-500 text-sm font-semibold mt-1">
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