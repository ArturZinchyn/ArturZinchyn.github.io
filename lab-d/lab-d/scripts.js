const apiKey = "49af78552cb37b476740fb41509de859";

const btn = document.getElementById("check-weather");
const input = document.getElementById("searchInput");

btn.addEventListener("click", () => {
  const city = input.value.trim();
  if (!city) return;

  getCurrentWeather(city);
  getForecast(city);
});

function getCurrentWeather(city) {
  const xhr = new XMLHttpRequest();

  const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=pl`;

  xhr.open("GET", url, true);

  xhr.onload = function () {
    const data = JSON.parse(xhr.responseText);

    console.log("CURRENT WEATHER:", JSON.stringify(data, null, 2));

    if (xhr.status !== 200 || data.cod !== 200) {
      document.getElementById("currentWeather").innerHTML =
        "<p>Błąd pobierania pogody</p>";
      return;
    }

    displayCurrent(data);
  };

  xhr.onerror = function () {
    console.log("Błąd żądania XMLHttpRequest");
  };

  xhr.send();
}

function getForecast(city) {
  const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}&units=metric&lang=pl`;

  fetch(url)
    .then(res => res.json())
    .then(data => {
      console.log("FORECAST:", JSON.stringify(data, null, 2));

      if (data.cod !== "200") {
        document.getElementById("forecast").innerHTML =
          "<p>Błąd pobierania prognozy</p>";
        return;
      }

      displayForecast(data);
    })
    .catch(() => {
      console.log("Błąd fetch");
    });
}

function displayCurrent(data) {
  const box = document.getElementById("currentWeather");

  box.innerHTML = `
    <h2>Aktualna pogoda</h2>
    <p><b>Miasto:</b> ${data.name}</p>
    <p><b>Temperatura:</b> ${data.main.temp} °C</p>
    <p><b>Opis:</b> ${data.weather[0].description}</p>
  `;
}

function displayForecast(data) {
  const box = document.getElementById("forecast");

  box.innerHTML = "<h2>Prognoza</h2>";

  if (!data.list) {
    box.innerHTML += "<p>Brak danych</p>";
    return;
  }

  data.list.slice(0, 8).forEach(item => {
    const div = document.createElement("div");
    div.className = "forecast-item";

    div.innerHTML = `
      <p>${item.dt_txt}</p>
      <p>${item.main.temp} °C</p>
      <p>${item.weather[0].description}</p>
    `;

    box.appendChild(div);
  });
}
