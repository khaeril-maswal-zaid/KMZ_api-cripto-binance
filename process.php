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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Periksa apakah data berhasil diambil
    if ($_GET['simbol']) {
        $data = $_GET['simbol'];

        // Koneksi ke database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "kmz_criptohistory";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Query SQL untuk insert
            $sql = "SELECT * FROM prices WHERE type_cripto = :type_cripto ORDER BY time_high_price ASC LIMIT 8";
            $stmt = $conn->prepare($sql);

            //Bind Prams
            $stmt->bindParam(':type_cripto',  $data, PDO::PARAM_STR);

            // Eksekusi query
            $stmt->execute();

            $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(["status" => "success", "data" => $resul]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        $conn = null; // Tutup koneksi
    } else {
        echo json_encode(["status" => "error", "message" => "Simbol tidak valid."]);
    }
    exit;
}
