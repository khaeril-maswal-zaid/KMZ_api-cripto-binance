<?php
// Jika request menggunakan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari request body
    $data = json_decode(file_get_contents("php://input"), true);
    // $data = $_REQUEST;

    function isDuplikat($conn, $timeHigh, $timeLow)
    {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM prices WHERE time_high_price = :timeHigh OR time_low_price = :timeLow");
        $stmt->execute(['timeHigh' => $timeHigh, 'timeLow' => $timeLow]);
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


            if (isDuplikat($conn, $data['timeHighPrice'], $data['timeLowPrices'])) {
                echo json_encode(["status" => "error", "message" => "Data Duplikat"]);
            } else {

                // Data yang akan dimasukkan
                $data = [
                    'type_cripto' => "BTC",
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

    <script>
        <?php
        $timestamp = round(microtime(true) * 1000);

        // Waktu pukul 8 pagi hari ini
        $today_8am = strtotime("today") * 1000;

        // Jika sekarang masih sebelum pukul 8 pagi, ambil pukul 8 pagi kemarin
        if ($timestamp < $today_8am) {
            $today_8amx = strtotime("yesterday") * 1000;
        }

        $apiEnd = $today_8am;
        $apiStart = $apiEnd - 24 * 60 * 60 * 1000;
        ?>


        document.addEventListener('DOMContentLoaded', async function() {
            function formatNumber(num) {
                return num.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            try {
                const apiUrl = `https://api.binance.com/api/v3/klines?symbol=BTCUSDT&interval=1h&startTime=<?= $apiStart ?>&endTime=<?= $apiEnd ?>`;
                const apiResponse = await fetch(apiUrl);
                const data = await apiResponse.json();

                const allPighPrices = data.map(candle => parseFloat(candle[2]));
                const allLowPrices = data.map(candle => parseFloat(candle[3]));
                const times = data.map(candle => new Date(candle[0]));

                const highestPriceIndex = allPighPrices.indexOf(Math.max(...allPighPrices));
                const lowestPriceIndex = allLowPrices.indexOf(Math.min(...allLowPrices));

                const signif = formatNumber(((allPighPrices[highestPriceIndex] - allLowPrices[lowestPriceIndex]) / allLowPrices[lowestPriceIndex]) * 100) + "%";

                const highPrice = `$${formatNumber(allPighPrices[highestPriceIndex])}`;
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
                        highPrice,
                        timeHighPrice,
                        signif,
                        lowPrices,
                        timeLowPrices,
                    })
                });

                const result = await response.json();
                alert(result.message); // Tampilkan pesan dari server
                console.log(result.message); // Tampilkan pesan dari server
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan!');
            }
        })
    </script>
</body>

</html>