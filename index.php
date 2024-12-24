<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Binance Historical Data</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      margin: 20px;
      color: #333;
      background-color: #f9f9f9;
    }

    h1 {
      text-align: center;
      color: #444;
      margin-bottom: 0px;
    }

    h3 {
      margin-bottom: 20px;
      color: #555;
      text-align: center;
    }

    p {
      text-align: center;
      margin: 0px auto 20px auto;
      font-style: italic;
    }

    select {
      display: block;
      margin: 20px auto;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #fff;
      cursor: pointer;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      /* margin: 20px 0; */
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
      padding: 15px;
      text-align: center;
      border: 1px solid #ddd;
    }

    th {
      background-color: #f0f0f0;
      color: #555;
      font-weight: bold;
    }

    tbody tr:hover {
      background-color: #f7f7f7;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .scrollable {
      overflow-x: auto;
      /* padding: 10px; */
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: rgb(246, 246, 246);
      margin-bottom: 35px;
    }

    @media (max-width: 768px) {
      body {
        margin: 10px;
      }

      h1 {
        font-size: 1.5em;
      }

      h3 {
        font-size: 1.2em;
      }

      table,
      th,
      td {
        font-size: 0.9em;
      }

      select {
        width: 90%;
      }
    }
  </style>
</head>

<body>
  <h1>Binance Historical Data</h1>
  <p>Data Presented by Al-Zaid Finance</p>

  <select name="cripto" id="cripto">
    <option value="BTCUSDT">BTC-USDT</option>
    <option value="ETHUSDT">ETH-USDT</option>
    <option value="BNBUSDT">BNB-USDT</option>
    <option value="XRPUSDT">XRP-USDT</option>
    <option value="ADAUSDT">ADA-USDT</option>
    <option value="SOLUSDT">SOL-USDT</option>
    <option value="DOGEUSDT">DOGE-USDT</option>
    <option value="MATICUSDT">MATIC-USDT</option>
    <option value="DOTUSDT">DOT-USDT</option>
    <option value="AVAXUSDT">AVAX-USDT</option>
  </select>

  <!-- Tabel data historis -->
  <div class="scrollable">
    <h3 id="real">Real Time BTC-USDT</h3>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Time</th>
          <th>Open Price</th>
          <th>Close Price</th>
        </tr>
      </thead>
      <tbody id="data-table">
        <!-- Data will be dynamically inserted here -->
      </tbody>
    </table>
  </div>

  <!-- Tabel harga 24 jam terakhir -->
  <div class="scrollable">
    <h3 id="hour">24-Hour High/Low BTC-USDT</h3>
    <table>
      <thead>
        <tr>
          <th>Highest Price</th>
          <th>Time</th>
          <th>Lowest Price</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody id="daily-high-low">
        <tr>
          <td id="day-high">-</td>
          <td id="day-high-time">-</td>
          <td id="day-low">-</td>
          <td id="day-low-time">-</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Tabel harga 1 minggu terakhir -->
  <div class="scrollable">
    <h3 id="week">1-Week High/Low BTC-USDT</h3>
    <table>
      <thead>
        <tr>
          <th>Highest Price</th>
          <th>Time</th>
          <th>Lowest Price</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody id="weekly-high-low">
        <tr>
          <td id="week-high">-</td>
          <td id="week-high-time">-</td>
          <td id="week-low">-</td>
          <td id="week-low-time">-</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Tabel harga 1 bulan terakhir -->
  <div class="scrollable">
    <h3 id="month">1-Month High/Low BTC-USDT</h3>
    <table>
      <thead>
        <tr>
          <th>Highest Price</th>
          <th>Time</th>
          <th>Lowest Price</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody id="monthly-high-low">
        <tr>
          <td id="month-high">-</td>
          <td id="month-high-time">-</td>
          <td id="month-low">-</td>
          <td id="month-low-time">-</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Tabel harga 1 tahun terakhir -->
  <div class="scrollable">
    <h3 id="year">1-Year High/Low BTC-USDT</h3>
    <table>
      <thead>
        <tr>
          <th>Highest Price</th>
          <th>Time</th>
          <th>Lowest Price</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody id="yearly-high-low">
        <tr>
          <td id="year-high">-</td>
          <td id="year-high-time">-</td>
          <td id="year-low">-</td>
          <td id="year-low-time">-</td>
        </tr>
      </tbody>
    </table>
  </div>

  <script>
    const tableBody = document.getElementById('data-table');

    const dayHighEl = document.getElementById('day-high');
    const dayLowEl = document.getElementById('day-low');
    const dayHighTimeEl = document.getElementById('day-high-time');
    const dayLowTimeEl = document.getElementById('day-low-time');

    const weekHighEl = document.getElementById('week-high');
    const weekLowEl = document.getElementById('week-low');
    const weekHighTimeEl = document.getElementById('week-high-time');
    const weekLowTimeEl = document.getElementById('week-low-time');

    const monthHighEl = document.getElementById('month-high');
    const monthLowEl = document.getElementById('month-low');
    const monthHighTimeEl = document.getElementById('month-high-time');
    const monthLowTimeEl = document.getElementById('month-low-time');

    const yearHighEl = document.getElementById('year-high');
    const yearLowEl = document.getElementById('year-low');
    const yearHighTimeEl = document.getElementById('year-high-time');
    const yearLowTimeEl = document.getElementById('year-low-time');

    function formatNumber(num) {
      return num.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    const selectElement = document.getElementById('cripto');
    let selectedSymbol = selectElement.value; // Default value saat pertama kali halaman dimuat

    const pairTitle = document.getElementById('hour');

    // Event listener untuk update symbol saat dropdown berubah
    selectElement.addEventListener('change', () => {
      selectedSymbol = selectElement.value;
      fetchHistoricalData(); // Fetch data lagi dengan symbol yang baru
      fetchData(); // Fetch data tabel lagi

      const selectedValue = selectElement.options[selectElement.selectedIndex].text; // Get selected text
      document.getElementById('real').textContent = "Real Time " + selectedValue;
      document.getElementById('hour').textContent = "1-Hour High/Low " + selectedValue;
      document.getElementById('week').textContent = "1-Week High/Low " + selectedValue;
      document.getElementById('month').textContent = "1-Month High/Low " + selectedValue;
      document.getElementById('year').textContent = "1-Year High/Low " + selectedValue;
    });



    function fetchHistoricalData() {
      const endTime = Date.now();
      const oneDayAgo = endTime - 24 * 60 * 60 * 1000;
      const TwoDayAgo = endTime - 24 * 60 * 60 * 1000;
      const ThereDayAgo = endTime - 24 * 60 * 60 * 1000;
      const oneWeekAgo = endTime - 7 * 24 * 60 * 60 * 1000;
      const oneMonthAgo = endTime - 30 * 24 * 60 * 60 * 1000;
      const oneYearAgo = endTime - 365 * 24 * 60 * 60 * 1000;

      const dailyUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1h&startTime=${oneDayAgo}&endTime=${endTime}`;
      fetch(dailyUrl)
        .then(response => response.json())
        .then(data => {
          const highPrices = data.map(candle => parseFloat(candle[2]));
          const lowPrices = data.map(candle => parseFloat(candle[3]));
          const times = data.map(candle => new Date(candle[0]));

          const highestPriceIndex = highPrices.indexOf(Math.max(...highPrices));
          const lowestPriceIndex = lowPrices.indexOf(Math.min(...lowPrices));

          dayHighEl.textContent = `$${formatNumber(highPrices[highestPriceIndex])}`;
          dayHighTimeEl.textContent = times[highestPriceIndex].toLocaleString();
          dayLowEl.textContent = `$${formatNumber(lowPrices[lowestPriceIndex])}`;
          dayLowTimeEl.textContent = times[lowestPriceIndex].toLocaleString();
        });

      const dail2yUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1h&startTime=${TwoDayAgo}&endTime=${endTime}`;
      fetch(dailyUrl)
        .then(response => response.json())
        .then(data => {
          const highPrices = data.map(candle => parseFloat(candle[2]));
          const lowPrices = data.map(candle => parseFloat(candle[3]));
          const times = data.map(candle => new Date(candle[0]));

          const highestPriceIndex = highPrices.indexOf(Math.max(...highPrices));
          const lowestPriceIndex = lowPrices.indexOf(Math.min(...lowPrices));

          day2HighEl.textContent = `$${formatNumber(highPrices[highestPriceIndex])}`;
          day2HighTimeEl.textContent = times[highestPriceIndex].toLocaleString();
          day2LowEl.textContent = `$${formatNumber(lowPrices[lowestPriceIndex])}`;
          day2LowTimeEl.textContent = times[lowestPriceIndex].toLocaleString();
        });

      const dail3yUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1h&startTime=${ThereDayAgo}&endTime=${endTime}`;
      fetch(dailyUrl)
        .then(response => response.json())
        .then(data => {
          const highPrices = data.map(candle => parseFloat(candle[2]));
          const lowPrices = data.map(candle => parseFloat(candle[3]));
          const times = data.map(candle => new Date(candle[0]));

          const highestPriceIndex = highPrices.indexOf(Math.max(...highPrices));
          const lowestPriceIndex = lowPrices.indexOf(Math.min(...lowPrices));

          day3HighEl.textContent = `$${formatNumber(highPrices[highestPriceIndex])}`;
          day3HighTimeEl.textContent = times[highestPriceIndex].toLocaleString();
          day3LowEl.textContent = `$${formatNumber(lowPrices[lowestPriceIndex])}`;
          day3LowTimeEl.textContent = times[lowestPriceIndex].toLocaleString();
        });

      const weeklyUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1d&startTime=${oneWeekAgo}&endTime=${endTime}`;
      fetch(weeklyUrl)
        .then(response => response.json())
        .then(data => {
          const highPrices = data.map(candle => parseFloat(candle[2]));
          const lowPrices = data.map(candle => parseFloat(candle[3]));
          const times = data.map(candle => new Date(candle[0]));

          const highestPriceIndex = highPrices.indexOf(Math.max(...highPrices));
          const lowestPriceIndex = lowPrices.indexOf(Math.min(...lowPrices));

          weekHighEl.textContent = `$${formatNumber(highPrices[highestPriceIndex])}`;
          weekHighTimeEl.textContent = times[highestPriceIndex].toLocaleString();
          weekLowEl.textContent = `$${formatNumber(lowPrices[lowestPriceIndex])}`;
          weekLowTimeEl.textContent = times[lowestPriceIndex].toLocaleString();
        });

      const monthlyUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1d&startTime=${oneMonthAgo}&endTime=${endTime}`;
      fetch(monthlyUrl)
        .then(response => response.json())
        .then(data => {
          const highPrices = data.map(candle => parseFloat(candle[2]));
          const lowPrices = data.map(candle => parseFloat(candle[3]));
          const times = data.map(candle => new Date(candle[0]));

          const highestPriceIndex = highPrices.indexOf(Math.max(...highPrices));
          const lowestPriceIndex = lowPrices.indexOf(Math.min(...lowPrices));

          monthHighEl.textContent = `$${formatNumber(highPrices[highestPriceIndex])}`;
          monthHighTimeEl.textContent = times[highestPriceIndex].toLocaleString();
          monthLowEl.textContent = `$${formatNumber(lowPrices[lowestPriceIndex])}`;
          monthLowTimeEl.textContent = times[lowestPriceIndex].toLocaleString();
        });

      const yearlyUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1w&startTime=${oneYearAgo}&endTime=${endTime}`;
      fetch(yearlyUrl)
        .then(response => response.json())
        .then(data => {
          const highPrices = data.map(candle => parseFloat(candle[2]));
          const lowPrices = data.map(candle => parseFloat(candle[3]));
          const times = data.map(candle => new Date(candle[0]));

          const highestPriceIndex = highPrices.indexOf(Math.max(...highPrices));
          const lowestPriceIndex = lowPrices.indexOf(Math.min(...lowPrices));

          yearHighEl.textContent = `$${formatNumber(highPrices[highestPriceIndex])}`;
          yearHighTimeEl.textContent = times[highestPriceIndex].toLocaleString();
          yearLowEl.textContent = `$${formatNumber(lowPrices[lowestPriceIndex])}`;
          yearLowTimeEl.textContent = times[lowestPriceIndex].toLocaleString();
        });
    }

    function fetchData() {
      const endTime = Date.now();

      // Fetch historical data for the table
      let sevenMinitsAgo = endTime - 10 * 1000;

      const tableUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1s&startTime=${sevenMinitsAgo}&endTime=${endTime}`;
      fetch(dailyUrl)
        .then(response => response.json())
        .then(data => {
          tableBody.innerHTML = ""; // Clear previous data

          const maxRows = 7; // Jumlah baris tetap
          const filledData = data.slice(0, maxRows); // Batasi data jika lebih

          // Render data yang tersedia
          filledData.reverse().forEach((candle, index) => {
            const openTime = new Date(candle[0]).toLocaleString();
            const openPrice = `$${formatNumber(parseFloat(candle[1]))}`;
            const closePrice = `$${formatNumber(parseFloat(candle[4]))}`;

            const row = document.createElement('tr');
            row.innerHTML = `
          <td>${index + 1}</td>
          <td>${openTime}</td>
          <td>${openPrice}</td>
          <td>${closePrice}</td>
        `;
            tableBody.appendChild(row);
          });

          // Tambahkan baris kosong jika data kurang dari maxRows
          const emptyRows = maxRows - filledData.length;
          for (let i = 0; i < emptyRows; i++) {
            const row = document.createElement('tr');
            row.innerHTML = `
          <td>${filledData.length + i + 1}</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
        `;
            tableBody.appendChild(row);
          }
        })
        .catch(error => {
          console.error("Error fetching data:", error);
        });
    }


    // Panggil fetchData setiap detik
    setInterval(fetchData, 1300);

    fetchHistoricalData();
    fetchData();
  </script>
</body>

</html>