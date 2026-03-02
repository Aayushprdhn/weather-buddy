const searchBox = document.getElementById("city");
const searchBtn = document.getElementById("Btn");

function getWeatherIcon(condition) {
    switch (condition.toLowerCase()) {
        case "clear": return "wb_sunny";      
        case "clouds": return "cloud";       
        case "rain":
        case "drizzle": return "umbrella";     
        case "thunderstorm": return "thunderstorm";
        case "snow": return "ac_unit";         
        case "mist":
        case "fog": return "water_drop";       
        default: return "cloud";
    }
}

async function checkWeather(city = 'Nottingham') {
    try {
        const response = await fetch(`http://localhost/prototype2/connection.php?q=${city}`);
        const data = await response.json();

        if (response.ok && Array.isArray(data) && data.length > 0) {
            const weather = data[0];

            document.querySelector(".city").innerHTML = weather.city;
            document.querySelector(".temp").innerHTML = Math.floor(weather.temperature) + "°C";
            document.querySelector(".humidity").innerHTML = weather.humidity + "%";
            document.querySelector(".wind").innerHTML = weather.wind_speed + " m/s";
            document.querySelector(".pressure").innerHTML = weather.pressure + " hPa";
            document.querySelector(".wind-direction").innerHTML = weather.wind_direction + "°";
            document.querySelector(".condition").innerHTML = weather.condition;

            
            const iconName = getWeatherIcon(weather.condition);
            document.querySelector(".weather-icon").textContent = iconName;

            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.querySelector(".date").innerHTML = now.toLocaleDateString('en-US', options);
        } else {
            document.querySelector(".city").innerHTML = "City not found!";
        }
    } catch (error) {
        console.error(error);
        document.querySelector(".city").innerHTML = "Error fetching data!";
    }
}

searchBtn.addEventListener("click", () => {
    checkWeather(searchBox.value);
    searchBox.value = "";
});

searchBox.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        checkWeather(searchBox.value);
        searchBox.value = "";
    }
});

checkWeather();