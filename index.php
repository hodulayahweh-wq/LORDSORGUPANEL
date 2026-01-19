<?php
/* ===============================
   LORD SORGU PANELI – STABIL
   Render uyumlu | Beyaz ekran FIX
================================ */

// HATA GÖSTER (beyaz ekran fix)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// STATIC DOSYA FIX (CSS / GIF / JS)
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file)) {
    return false;
}

// API ÇEK + TEMİZLE
function apiGetClean($url) {
    $ctx = stream_context_create([
        "http" => [
            "timeout" => 15,
            "header"  => "User-Agent: LORD-PANEL\r\n"
        ]
    ]);

    $raw = @file_get_contents($url, false, $ctx);
    if ($raw === false) {
        return ["hata" => "API yanıt vermiyor"];
    }

    $json = json_decode($raw, true);
    if (!$json) {
        return ["hata" => "Geçersiz JSON"];
    }

    // link / gereksiz alan temizle
    array_walk_recursive($json, function (&$v) {
        if (is_string($v) && preg_match('/http|www/i', $v)) {
            $v = "";
        }
    });

    return $json;
}

// TXT İNDİR
if (isset($_GET["download"]) && isset($_POST["data"])) {
    header("Content-Type: text/plain; charset=utf-8");
    header("Content-Disposition: attachment; filename=sonuc.txt");
    echo $_POST["data"];
    exit;
}

// FORM
$result = null;
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["api"])) {
    $result = apiGetClean($_POST["api"]);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>LORD SORGU PANELI</title>
<style>
body{
    margin:0;
    font-family:Arial,Helvetica,sans-serif;
    color:#fff;
    background:url("https://media.giphy.com/media/26tn33aiTi1jkl6H6/giphy.gif") no-repeat center center fixed;
    background-size:cover;
}
.overlay{
    background:rgba(0,0,0,.75);
    min-height:100vh;
    padding:20px;
}
.menu{
    background:#0a0a0a;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
}
.menu a{
    display:block;
    color:#00ff66;
    text-decoration:none;
    margin:8px 0;
    font-weight:bold;
}
.box{
    background:#111;
    padding:20px;
    border-radius:15px;
}
button{
    background:#00ff66;
    border:none;
    padding:12px 20px;
    font-weight:bold;
    border-radius:8px;
    cursor:pointer;
}
textarea{
    width:100%;
    height:250px;
    background:#000;
    color:#0f0;
    border-radius:10px;
    padding:10px;
}
.market{
    margin-top:20px;
    background:#080808;
    padding:15px;
    border-radius:12px;
}
.market button{
    width:100%;
    margin-top:10px;
}
</style>
</head>

<body>
<div class="overlay">

<div class="menu">
    <b>☰ LORD MENU</b>
    <a href="https://t.me/lordsystemv3" target="_blank">📲 Telegram Kanal</a>

    <hr>
    <b>⭐ Premium Sorgular</b>
    <a>IBAN Sorgu</a>
    <a>GSM → TC</a>
    <a>TC → GSM</a>
    <a>Ad Soyad</a>
    <a>Vergi Borcu</a>
    <a>Su Fatura</a>

    <hr>
    <b>⚠ Eğitim Amaçlı</b>
    <a>IP Bilgi</a>
    <a>Port Bilgi</a>
    <a>Hash Bilgi</a>
    <a>Log Analiz</a>
</div>

<div class="box">
<h2>🔍 API Sorgu</h2>

<form method="post">
    <select name="api" style="width:100%;padding:10px;border-radius:8px">
        <option value="">API Seç</option>
        <option value="https://lyranew.ct.ws/api/iban.php?iban=TR0000000000">IBAN</option>
        <option value="https://zyrdaware.xyz/api/tcgsm?auth=t.me/zyrdaware&tc=11111111111">TC → GSM</option>
        <option value="https://zyrdaware.xyz/api/gsmtc?auth=t.me/zyrdaware&gsm=5550000000">GSM → TC</option>
        <option value="https://nabisorguapis.onrender.com/api/v1/vergi/borc-sorgu?tc=11111111111">Vergi</option>
    </select>
    <br><br>
    <button>SORGULA</button>
</form>

<?php if ($result): 
    $pretty = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
<hr>
<form method="post" action="?download=1">
<textarea readonly><?=htmlspecialchars($pretty)?></textarea>
<input type="hidden" name="data" value="<?=htmlspecialchars($pretty)?>">
<br><br>
<button>TXT İNDİR</button>
</form>
<?php endif; ?>
</div>

<div class="market">
<h3>🛒 Premium Market</h3>
<p>1 Hafta VIP – 200 TL</p>
<p>1 Ay VIP – 600 TL</p>
<p>5 Ay VIP – 1500 TL</p>
<p>Sınırsız – 3000 TL</p>
<button onclick="window.open('https://t.me/LordDestekHat')">SATIN AL</button>
</div>

</div>
</body>
</html>
