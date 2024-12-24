<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Al-Zaid Webcrafters</title>
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
  <p>Data Presented by Al-Zaid Webcrafters</p>

  <select name="cripto" id="cripto">
    <option value="BTCUSDT">BTC-USDT</option>
    <option value="ETHUSDT">ETH-USDT</option>
    <option value="BNBUSDT">BNB-USDT</option>
    <option value="SOLUSDT">SOL-USDT</option>
    <option value="ADAUSDT">ADA-USDT</option>
    <option value="DOGEUSDT">DOGE-USDT</option>
    <option value="AVAXUSDT">AVAX-USDT</option>
    <option value="SHIBCUSDT">SHIB-USDT</option>
    <option value="PEPECUSDT">PEPE-USDT</option>
    <option value="DOTUSDT">DOT-USDT</option>
    <option value="XRPUSDT">XRP-USDT</option>
    <option value="PENGUUSDT">PENGU-USDT</option>
    <option value="LTCUSDT">LTC-USDT</option>
    <option value="LINKUSDT">LINK-USDT</option>
    <option value="XLMSDT">XXLMSDT</option>
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

  <?php
  $skalas = ['24-Hour', '7-Day', '1-Month', '1-Year'];
  foreach ($skalas as $key => $value) :
  ?>
    <div class="scrollable">
      <h3 id="<?= $value ?>">BTC-USDT Price High & Lows: <?= $value ?></h3>
      <table>
        <thead>
          <tr>
            <th>Highest Price</th>
            <th>Significant</th>
            <th>Lowest Price</th>
          </tr>
        </thead>
        <tbody id="<?= $value ?>-high-low">
          <tr>
            <td id="<?= $value ?>-high">-</td>
            <td id="<?= $value ?>-signif" rowspan="2">-</td>
            <td id="<?= $value ?>-low">-</td>
          </tr>
          <tr>
            <td id="<?= $value ?>-high-time">-</td>
            <td id="<?= $value ?>-low-time">-</td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php endforeach ?>

  <script>
    const tableBody = document.getElementById('data-table');

    <?php
    foreach ($skalas as $key => $value) :
    ?>
      const kmz<?= str_replace("-", "", $value,) ?>HighEl = document.getElementById('<?= $value ?>-high');
      const kmz<?= str_replace("-", "", $value,) ?>LowEl = document.getElementById('<?= $value ?>-low');
      const kmz<?= str_replace("-", "", $value,) ?>Signif = document.getElementById('<?= $value ?>-signif');
      const kmz<?= str_replace("-", "", $value,) ?>HighTimeEl = document.getElementById('<?= $value ?>-high-time');
      const kmz<?= str_replace("-", "", $value,) ?>LowTimeEl = document.getElementById('<?= $value ?>-low-time');
    <?php endforeach ?>

    function formatNumber(num) {
      return num.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    const selectElement = document.getElementById('cripto');
    let selectedSymbol = selectElement.value; // Default value saat pertama kali halaman dimuat

    // Event listener untuk update symbol saat dropdown berubah
    selectElement.addEventListener('change', () => {
      selectedSymbol = selectElement.value;
      fetchHistoricalData(); // Fetch data lagi dengan symbol yang baru
      fetchData(); // Fetch data tabel lagi

      const selectedValue = selectElement.options[selectElement.selectedIndex].text; // Get selected text

      <?php
      foreach ($skalas as $key => $value) :
      ?>
        document.getElementById('<?= $value ?>').textContent = selectedValue + " Price High & Lows: <?= $value ?>";
        document.getElementById('real').textContent = "Real Time " + selectedValue;
      <?php endforeach ?>
    });



    function fetchHistoricalData() {
      <?php
      $timestamp = round(microtime(true) * 1000);

      $oneDayAgo = $timestamp - 24 * 60 * 60 * 1000;
      $oneWeekAgo = $timestamp - 7 * 24 * 60 * 60 * 1000;
      $oneMonthAgo = $timestamp - 30 * 24 * 60 * 60 * 1000;
      $oneYearAgo = $timestamp - 365 * 24 * 60 * 60 * 1000;

      $start = [$oneDayAgo, $oneWeekAgo, $oneMonthAgo, $oneYearAgo];
      $interval = ["1h", "1d", "1d", "1w"];

      foreach ($skalas as $key => $value) :
      ?>
        const kmz<?= str_replace("-", "", $value,) ?> = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=<?= $interval[$key] ?>&startTime=<?= $start[$key] ?>&endTime=<?= $timestamp ?>`;
        fetch(kmz<?= str_replace("-", "", $value,) ?>)
          .then(response => response.json())
          .then(data => {
            const highPrices = data.map(candle => parseFloat(candle[2]));
            const lowPrices = data.map(candle => parseFloat(candle[3]));
            const times = data.map(candle => new Date(candle[0]));

            const highestPriceIndex = highPrices.indexOf(Math.max(...highPrices));
            const lowestPriceIndex = lowPrices.indexOf(Math.min(...lowPrices));
            const signif = formatNumber(((highPrices[highestPriceIndex] - lowPrices[lowestPriceIndex]) / lowPrices[lowestPriceIndex]) * 100);

            kmz<?= str_replace("-", "", $value,) ?>HighEl.textContent = `$${formatNumber(highPrices[highestPriceIndex])}`;
            kmz<?= str_replace("-", "", $value,) ?>HighTimeEl.textContent = times[highestPriceIndex].toLocaleString();

            kmz<?= str_replace("-", "", $value,) ?>Signif.textContent = signif + "%";

            kmz<?= str_replace("-", "", $value,) ?>LowEl.textContent = `$${formatNumber(lowPrices[lowestPriceIndex])}`;
            kmz<?= str_replace("-", "", $value,) ?>LowTimeEl.textContent = times[lowestPriceIndex].toLocaleString();
          });
      <?php endforeach ?>
    }

    function fetchData() {
      const endTime = Date.now();

      // Fetch historical data for the table
      let sevenMinitsAgo = endTime - 10 * 1000;

      const tableUrl = `https://api.binance.com/api/v3/klines?symbol=${selectedSymbol}&interval=1s&startTime=${sevenMinitsAgo}&endTime=${endTime}`;
      fetch(tableUrl)
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

    fetchHistoricalData();
    fetchData();

    // Panggil fetchData setiap 1,3 detik
    setInterval(fetchData, 1300);
  </script>
</body>

</html>