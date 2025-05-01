// API Key for OpenWeatherMap (replace with your own key)
const API_KEY = 'YOUR_API_KEY_HERE'; // Get one from https://openweathermap.org/api

// DOM Elements
const cityInput = document.getElementById('city-input');
const searchBtn = document.getElementById('search-btn');
const currentLocationBtn = document.getElementById('current-location-btn');
const weatherContainer = document.getElementById('weather-container');
const forecastContainer = document.getElementById('forecast-container');
const forecastCards = document.getElementById('forecast-cards');

// Weather display elements
const cityName = document.getElementById('city-name');
const country = document.getElementById('country');
const temp = document.getElementById('temp');
const weatherIcon = document.getElementById('weather-icon');
const weatherDescription = document.getElementById('weather-description');
const feelsLike = document.getElementById('feels-like');
const humidity = document.getElementById('humidity');
const wind = document.getElementById('wind');
const pressure = document.getElementById('pressure');

// Fetch weather data from API
async function fetchWeatherData(city) {
    try {
        // Show loading state
        weatherContainer.innerHTML = '<p>Loading...</p>';
        forecastContainer.style.display = 'none';

        // Fetch current weather
        const currentWeatherResponse = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${API_KEY}`
        );

        if (!currentWeatherResponse.ok) {
            throw new Error('City not found');
        }

        const currentWeatherData = await currentWeatherResponse.json();

        // Fetch forecast
        const forecastResponse = await fetch(
            `https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&appid=${API_KEY}`
        );
        const forecastData = await forecastResponse.json();

        return { current: currentWeatherData, forecast: forecastData };
    } catch (error) {
        console.error('Error fetching weather data:', error);
        weatherContainer.innerHTML = `<p>Error: ${error.message}</p>`;
        return null;
    }
}

// Display weather data
function displayWeatherData(data) {
    const { current, forecast } = data;

    // Display current weather
    cityName.textContent = current.name;
    country.textContent = current.sys.country;
    temp.textContent = Math.round(current.main.temp);
    weatherDescription.textContent = current.weather[0].description;
    feelsLike.textContent = `${Math.round(current.main.feels_like)}°C`;
    humidity.textContent = `${current.main.humidity}%`;
    wind.textContent = `${current.wind.speed} m/s`;
    pressure.textContent = `${current.main.pressure} hPa`;

    // Set weather icon
    const iconCode = current.weather[0].icon;
    weatherIcon.src = `https://openweathermap.org/img/wn/${iconCode}@2x.png`;
    weatherIcon.alt = current.weather[0].main;

    // Display forecast
    displayForecastData(forecast);

    // Show containers
    weatherContainer.style.display = 'block';
    forecastContainer.style.display = 'block';
}

// Display forecast data
function displayForecastData(forecastData) {
    // Clear previous forecast cards
    forecastCards.innerHTML = '';

    // Filter to get one forecast per day (every 24 hours)
    const dailyForecasts = [];
    const days = new Set();

    for (const forecast of forecastData.list) {
        const date = new Date(forecast.dt * 1000);
        const day = date.toLocaleDateString('en-US', { weekday: 'short' });

        if (!days.has(day) {
            days.add(day);
            dailyForecasts.push({
                day,
                temp: forecast.main.temp,
                feels_like: forecast.main.feels_like,
                icon: forecast.weather[0].icon,
                description: forecast.weather[0].description
            });

            // We only need 5 days
            if (dailyForecasts.length === 5) break;
        }
    }

    // Create forecast cards
    dailyForecasts.forEach(day => {
        const forecastCard = document.createElement('div');
        forecastCard.className = 'forecast-card';

        forecastCard.innerHTML = `
            <div class="forecast-day">${day.day}</div>
            <div class="forecast-icon">
                <img src="https://openweathermap.org/img/wn/${day.icon}.png" alt="${day.description}">
            </div>
            <div class="forecast-temp">
                <span>${Math.round(day.temp)}°</span>
            </div>
            <div class="forecast-desc">${day.description}</div>
        `;

        forecastCards.appendChild(forecastCard);
    });
}

// Get weather by current location
function getWeatherByLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const { latitude, longitude } = position.coords;

                try {
                    // Show loading state
                    weatherContainer.innerHTML = '<p>Loading...</p>';
                    forecastContainer.style.display = 'none';

                    // Fetch weather by coordinates
                    const response = await fetch(
                        `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&units=metric&appid=${API_KEY}`
                    );

                    if (!response.ok) {
                        throw new Error('Unable to fetch weather for your location');
                    }

                    const currentWeatherData = await response.json();

                    // Fetch forecast
                    const forecastResponse = await fetch(
                        `https://api.openweathermap.org/data/2.5/forecast?lat=${latitude}&lon=${longitude}&units=metric&appid=${API_KEY}`
                    );
                    const forecastData = await forecastResponse.json();

                    displayWeatherData({ current: currentWeatherData, forecast: forecastData });
                    cityInput.value = currentWeatherData.name;
                } catch (error) {
                    console.error('Error fetching weather by location:', error);
                    weatherContainer.innerHTML = `<p>Error: ${error.message}</p>`;
                }
            },
            (error) => {
                console.error('Geolocation error:', error);
                weatherContainer.innerHTML = '<p>Unable to access your location. Please enable location services.</p>';
            }
        );
    } else {
        weatherContainer.innerHTML = '<p>Geolocation is not supported by your browser</p>';
    }
}

// Event listeners
searchBtn.addEventListener('click', async () => {
    const city = cityInput.value.trim();
    if (city) {
        const weatherData = await fetchWeatherData(city);
        if (weatherData) {
            displayWeatherData(weatherData);
        }
    } else {
        weatherContainer.innerHTML = '<p>Please enter a city name</p>';
    }
});

currentLocationBtn.addEventListener('click', getWeatherByLocation);

cityInput.addEventListener('keypress', async (e) => {
    if (e.key === 'Enter') {
        const city = cityInput.value.trim();
        if (city) {
            const weatherData = await fetchWeatherData(city);
            if (weatherData) {
                displayWeatherData(weatherData);
            }
        } else {
            weatherContainer.innerHTML = '<p>Please enter a city name</p>';
        }
    }
});

// Initialize with a default city
window.addEventListener('load', () => {
    fetchWeatherData('London').then(data => {
        if (data) {
            displayWeatherData(data);
        }
    });
});