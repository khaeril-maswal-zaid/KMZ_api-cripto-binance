<?php
// Jika request menggunakan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari request body
    $data = json_decode(file_get_contents("php://input"), true);
    // $data = $_REQUEST;

    function isDuplikat($conn, $timeHigh, $symbol)
    {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM prices WHERE time_high_price = :timeHigh AND type_cripto = :symbol");
        $stmt->execute(['timeHigh' => $timeHigh, 'symbol' => $symbol]);
        return $stmt->fetchColumn() > 0;
    }

    // Periksa apakah data berhasil diambil
    if ($data) {
        // Koneksi ke database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "kmz_criptohistory";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            if (isDuplikat($conn, $data['timeHighPrice'], $data['symbol'])) {
                echo json_encode(["status" => "error", "message" => "Data Duplikat"]);
            } else {

                // Data yang akan dimasukkan
                $data = [
                    'type_cripto' => $data['symbol'],
                    'high_price' => $data['highPrice'],
                    'time_high_price' => $data['timeHighPrice'],
                    'significant' => $data['signif'],
                    'low_price' => $data['lowPrices'],
                    'time_low_price' => $data['timeLowPrices'],
                    'created_at' => date('Y-m-d H:i:s'), // Otomatis mengambil waktu sekarang
                ];

                // Query SQL untuk insert
                $sql = "INSERT INTO prices (type_cripto, high_price, time_high_price, significant, low_price, time_low_price, created_at)
                        VALUES (:type_cripto, :high_price, :time_high_price, :significant, :low_price, :time_low_price, :created_at)";

                // Persiapkan query
                $stmt = $conn->prepare($sql);

                // Eksekusi query
                $stmt->execute($data);

                echo json_encode(["status" => "success", "message" => "Data berhasil disimpan."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }

        $conn = null; // Tutup koneksi
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak valid."]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process save data</title>
</head>

<body>

    <!-- UNTUK CEK MANUAL JIKA API HOLLO -->
    <!-- <form action="" method="post">
        <input type="text" name="highPrice" value="12">
        <input type="text" name="timeHighPrice" value="08:00">
        <input type="text" name="signif" value="17%">
        <input type="text" name="lowPrices" value="12">
        <input type="text" name="timeLowPrices" value="08:00">

        <button type="submit">Ok</button>
    </form> -->

    <div id="loading" style="display: nonex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); color: white; font-size: 24px; display: flex; justify-content: center; align-items: center; z-index: 9999;">
        <p id="massage">Proses... </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const cryptoSymbols = ["BTCUSDT", "ETHUSDT", "BNBUSDT", "SOLUSDT", "ADAUSDT", "DOGEUSDT", "AVAXUSDT", "SHIBUSDT", "PEPEUSDT", "DOTUSDT", "XRPUSDT", "PENGUUSDT", "LTCUSDT", "LINKUSDT", "XLMUSDT"]; // Tambahkan simbol lain di sini

            const now = new Date();
            const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const plih = 4;
            const apiEnd = startOfDay - plih * 24 * 60 * 60 * 1000;
            const apiStart = startOfDay - (plih + 1) * 24 * 60 * 60 * 1000;

            // console.log(new Date(apiStart).toLocaleString(), new Date(apiEnd).toLocaleString());

            function formatNumber(num) {
                return num.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            const loading = document.getElementById('loading');
            const massage = document.getElementById('massage');
            loading.style.display = 'flex';

            for (const symbol of cryptoSymbols) {
                try {
                    const apiUrl = `https://api.binance.com/api/v3/klines?symbol=${symbol}&interval=15m&startTime=${apiStart}&endTime=${apiEnd}`;
                    const apiResponse = await fetch(apiUrl);
                    const data = await apiResponse.json();

                    const allHighPrices = data.map(candle => parseFloat(candle[2]));
                    const allLowPrices = data.map(candle => parseFloat(candle[3]));
                    const times = data.map(candle => new Date(candle[0]));

                    const highestPriceIndex = allHighPrices.indexOf(Math.max(...allHighPrices));
                    const lowestPriceIndex = allLowPrices.indexOf(Math.min(...allLowPrices));

                    const signif = formatNumber(((allHighPrices[highestPriceIndex] - allLowPrices[lowestPriceIndex]) / allLowPrices[lowestPriceIndex]) * 100) + "%";

                    const highPrice = `$${formatNumber(allHighPrices[highestPriceIndex])}`;
                    const timeHighPrice = times[highestPriceIndex].toLocaleString();

                    const lowPrices = `$${formatNumber(allLowPrices[lowestPriceIndex])}`;
                    const timeLowPrices = times[lowestPriceIndex].toLocaleString();

                    // Kirim data ke PHP menggunakan fetch
                    const response = await fetch(window.location.href, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            symbol, // Simbol yang sedang diproses
                            highPrice,
                            timeHighPrice,
                            signif,
                            lowPrices,
                            timeLowPrices,
                        })
                    });

                    const result = await response.json();
                    console.log(`${symbol} - ${result.message}`); // Tampilkan pesan dari server per simbol
                    massage.textContent = `${symbol} - ${result.message}...`;
                } catch (error) {
                    console.error(`Error for ${symbol}:`, error);
                    massage.textContent = `Terjadi kesalahan pada simbol ${symbol}..!`;
                    // alert(`Terjadi kesalahan pada ${symbol}!`);
                }
            }

            loading.style.display = 'none';
            window.location.href = "index.php";
        });
    </script>
</body>

</html>