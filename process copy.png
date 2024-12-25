<?php
// Jika request menggunakan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari request body
    $data = json_decode(file_get_contents("php://input"), true);

    // echo json_encode(["status" => "error", "message" => $data]);

    // $data = $_REQUEST;

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
        <input type="text" name="timeHighPrice" value="sds">
        <input type="text" name="signif" value="signif">
        <input type="text" name="lowPrices" value="12">
        <input type="text" name="timeLowPrices" value="timeLowPrices">

        <button type="submit">Ok</button>
    </form> -->
    <script>
        <?php
        $timestamp = time();

        // Waktu pukul 8 pagi hari ini
        $today_8am = strtotime("today 8:00");

        // Jika sekarang masih sebelum pukul 8 pagi, ambil pukul 8 pagi kemarin
        if ($timestamp < $today_8am) {
            $last_8am = strtotime("yesterday 8:00");
        } else {
            $last_8am = $today_8am;
        }

        $apiEnd = $last_8am  * 1000;
        $apiStart = $apiEnd - 24 * 60 * 60 * 1000;

        // Menampilkan detik waktu terakhir pukul 8 pagi
        echo "Detik terakhir pukul 8 pagi: " . $last_8am;

        $oneDayAgo = $timestamp - 24 * 60 * 60 * 1000;

        die;
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