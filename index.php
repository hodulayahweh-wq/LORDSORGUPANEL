<!--
LORD SORGU PANELI – STABIL SURUM (TEMIZ VERI + TXT INDIRME)
Not: "Hack" basliklari yalnizca Egitim/Farkindalik icerigidir.
--><?php
session_start();

// ===== AUTH =====
$ADMIN_USER = 'lordxpanel';
$ADMIN_PASS = 'lord2026free';

if(isset($_POST['username'], $_POST['password'])){
  if($_POST['username']===$ADMIN_USER && $_POST['password']===$ADMIN_PASS){
    $_SESSION['role']='admin';
  } else { $error='Hatali giris'; }
}
if(isset($_GET['logout'])){ session_destroy(); header('Location: ./'); exit; }

// ===== PROXY (CORS SAFE + CLEAN JSON) =====
if(isset($_GET['proxy']) && isset($_SESSION['role'])){
  header('Content-Type: application/json; charset=utf-8');
  $url = $_GET['proxy'];
  $ctx = stream_context_create([
    'http'=>['timeout'=>20,'ignore_errors'=>true],
    'ssl'=>['verify_peer'=>false,'verify_peer_name'=>false]
  ]);
  $raw = @file_get_contents($url,false,$ctx);
  if($raw===false){ echo json_encode(['hata'=>'API yanit vermiyor'],JSON_UNESCAPED_UNICODE); exit; }
  // Temiz JSON cikarma (HTML vs. kirp)
  $start = strpos($raw,'{');
  $end = strrpos($raw,'}');
  if($start!==false && $end!==false){ $raw = substr($raw,$start,$end-$start+1); }
  $json = json_decode($raw,true);
  if(!$json){ echo json_encode(['hata'=>'Gecersiz JSON'],JSON_UNESCAPED_UNICODE); exit; }
  // GEREKSIZ ALANLAR VE LINK TEMIZLEME
  $removeKeys = ['apiSahibi','apiDiscordSunucusu','apiTelegramGrubu'];
  foreach($removeKeys as $k){ if(isset($json[$k])) unset($json[$k]); }
  array_walk_recursive($json,function (&$v){
    if(is_string($v)){
      $v = preg_replace("~https?://[^\s]+~","",$v);
      $v = preg_replace("~t\.me/[^\s]+~","",$v);
      $v = preg_replace("~discord\.gg/[^\s]+~","",$v);
    }
  });
  echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
  exit; }
  echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
  exit;
}
?><!doctype html>

<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LORD PANEL</title>
<style>
:root{--neon:#00ff41;--bg:#000;--card:#0b0b0b}
*{box-sizing:border-box}
body{margin:0;font-family:system-ui,Arial;background:var(--bg);color:#fff}
/* 3D Hacker GIF BG */
.bg{position:fixed;inset:0;background:url('https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExb2N0cXl1eG1kZHR4eHk1b3FjY3l6a2F5d2Q3Z2RrZ3R3dWQ5cSZlcD12MV9naWZzX3NlYXJjaCZjdD1n/26tn33aiTi1jkl6H6/giphy.gif') center/cover no-repeat;filter:contrast(120%) saturate(120%);z-index:-2}
.ov{position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:-1}.nav{position:sticky;top:0;background:#000;padding:12px;border-bottom:1px solid #111;display:flex;gap:10px} .burger{cursor:pointer;color:var(--neon);font-size:22px} .side{position:fixed;left:-260px;top:0;width:250px;height:100%;background:#050505;transition:.3s;z-index:5;overflow:auto} .side.active{left:0} .side a{display:block;padding:12px 16px;color:#ccc;text-decoration:none;border-bottom:1px solid #111} .side h6{margin:10px 16px;color:var(--neon);font-size:11px} .main{max-width:820px;margin:auto;padding:16px} .card{background:var(--card);border:1px solid #111;border-radius:12px;padding:16px;margin-bottom:12px} input,button{width:100%;padding:12px;margin-top:8px;background:#000;border:1px solid #222;color:#fff;border-radius:8px} button{background:var(--neon);color:#000;font-weight:800;cursor:pointer} .res{white-space:pre-wrap;font-family:ui-monospace;color:var(--neon);margin-top:10px;display:none;max-height:260px;overflow:auto} .hidden{display:none} .badge{color:#ffd700} .btn-blue{display:block;width:100%;padding:12px;margin-top:10px;background:#0095f6;color:#fff;text-align:center;border-radius:8px;font-weight:800;text-decoration:none} .btn-blue:hover{opacity:.9} </style>

</head>
<body>
<div class="bg"></div><div class="ov"></div><?php if(!isset($_SESSION['role'])): ?><div class="main"><div class="card">
<h2>Giris</h2>
<form method="post">
<input name="username" placeholder="Kullanici">
<input name="password" type="password" placeholder="Sifre">
<button>GIRIS</button>
</form>
<?php if(isset($error)) echo '<p style="color:#f33">'.$error.'</p>'; ?>
</div></div>
<?php else: ?><div class="nav"><div class="burger" onclick="toggle()">☰</div><b>LORD</b></div>
<div class="side" id="side">
<h6>FREE</h6>
<a onclick="go('gsmtc')">GSM ➜ TC</a>
<a onclick="go('tcgsm')">TC ➜ GSM</a>
<a onclick="go('adsoyad')">Ad Soyad</a>
<a onclick="go('iban')">IBAN</a>
<a onclick="go('recete')">Recete</a>
<a onclick="go('istanbul')">Istanbul Kart</a>
<a onclick="go('vergi')">Vergi</a>
<a onclick="go('su')">IBB Su</a>
<a onclick="go('gsm2015')">GSM 2015</a>
<h6>PREMIUM</h6>
<a class="badge" onclick="market()">Aile / Sulale 🔒</a>
<a class="badge" onclick="market()">Tapu 🔒</a>
<a class="badge" onclick="market()">Plaka 🔒</a>
<a class="badge" onclick="market()">Adres Detay 🔒</a>
<a class="badge" onclick="market()">Banka Iliski 🔒</a>
<a class="badge" onclick="market()">SGK Kayit 🔒</a>
<h6>SIBER GUVENLIK</h6>
<a class="badge" onclick="market()">IP Analiz (Egitim) 🔒</a>
<a class="badge" onclick="market()">Port Tarama (Demo) 🔒</a>
<a class="badge" onclick="market()">OSINT Profil 🔒</a>
<a class="badge" onclick="market()">Log Okuyucu 🔒</a>
<h6>SISTEM</h6>
<a href="https://t.me/lordsystemv3" target="_blank">📨 Telegram Kanal</a>
<a href="?logout=1" style="color:#f33">Cikis</a>
</div><div class="main">
<div id="home" class="card">Hos geldin. Menuden sorgu sec.</div><div id="gsmtc" class="card hidden"><h3>GSM ➜ TC</h3>
<input id="g1" placeholder="05xxxxxxxxx"><button onclick="call('https://zyrdaware.xyz/api/gsmtc?auth=t.me/zyrdaware&gsm=', 'g1','r1')">SORGULA</button><div id="r1" class="res"></div></div><div id="tcgsm" class="card hidden"><h3>TC ➜ GSM</h3>
<input id="g2" placeholder="TC"><button onclick="call('https://zyrdaware.xyz/api/tcgsm?auth=t.me/zyrdaware&tc=', 'g2','r2')">SORGULA</button><div id="r2" class="res"></div></div><div id="adsoyad" class="card hidden"><h3>Ad Soyad</h3>
<input id="a1" placeholder="Ad"><input id="a2" placeholder="Soyad"><button onclick="call2('https://zyrdaware.xyz/api/adsoyad?auth=t.me/zyrdaware&ad=','&soyad=', 'a1','a2','r3')">SORGULA</button><div id="r3" class="res"></div></div><div id="iban" class="card hidden"><h3>IBAN</h3>
<input id="i1" placeholder="TR"><button onclick="call('https://lyranew.ct.ws/api/iban.php?iban=', 'i1','r4')">SORGULA</button><div id="r4" class="res"></div></div><div id="recete" class="card hidden"><h3>Recete</h3>
<input id="rci" placeholder="TC"><button onclick="call('https://nabisorguapis.onrender.com/api/v1/eczane/recete-gecmisi?tc=', 'rci','r5')">SORGULA</button><div id="r5" class="res"></div></div><div id="istanbul" class="card hidden"><h3>Istanbul Kart</h3>
<input id="ici" placeholder="TC"><button onclick="call('https://nabisorguapis.onrender.com/api/v1/ulasim/istanbulkart-bakiye?tc=', 'ici','r6')">SORGULA</button><div id="r6" class="res"></div></div><div id="vergi" class="card hidden"><h3>Vergi</h3>
<input id="vci" placeholder="TC"><button onclick="call('https://nabisorguapis.onrender.com/api/v1/vergi/borc-sorgu?tc=', 'vci','r7')">SORGULA</button><div id="r7" class="res"></div></div><div id="su" class="card hidden"><h3>IBB Su</h3>
<input id="sci" placeholder="TC"><button onclick="call('https://nabisorguapis.onrender.com/api/v1/ibb/su-fatura?tc=', 'sci','r8')">SORGULA</button><div id="r8" class="res"></div></div><div id="gsm2015" class="card hidden"><h3>GSM 2015</h3>
<input id="g15" placeholder="GSM"><button onclick="call('https://gamebzhhshs.onrender.com/api/v1/search/gsm_tc_2015?gsm=', 'g15','r9')">SORGULA</button><div id="r9" class="res"></div></div><div id="market" class="card hidden">
  <h2 class="badge">VIP MARKET</h2>
  <ul style="list-style:none;padding:0">
    <li>1 Haftalık <b>200 TL</b></li>
    <li>1 Aylık <b>600 TL</b></li>
    <li>5 Aylık <b>1500 TL</b></li>
    <li>Sınırsız <b>3000 TL</b></li>
  </ul>
  <a class="btn-blue" href="https://t.me/LordDestekHat" target="_blank">Telegram'dan Satın Al</a>
  <button class="btn" onclick="go('home')" style="margin-top:10px">Geri</button>
</div>
</div><script>
function toggle(){document.getElementById('side').classList.toggle('active')}
function go(id){document.querySelectorAll('.card').forEach(x=>x.classList.add('hidden'));document.getElementById(id).classList.remove('hidden');toggle()}
function market(){go('market')}
async function call(base,input,res){const v=document.getElementById(input).value;const r=document.getElementById(res);if(!v)return;r.style.display='block';r.textContent='Yukleniyor...';const t=await fetch('?proxy='+encodeURIComponent(base+v)).then(x=>x.text());
// buyuk veri txt indir
if(t.length>5000){const blob=new Blob([t],{type:'text/plain'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='sonuc.txt';a.click();r.textContent='Buyuk veri algilandi. TXT indirildi.';}else{r.textContent=t}}
async function call2(b1,b2,i1,i2,res){const v1=document.getElementById(i1).value;const v2=document.getElementById(i2).value;const r=document.getElementById(res);if(!v1||!v2)return;r.style.display='block';r.textContent='Yukleniyor...';const t=await fetch('?proxy='+encodeURIComponent(b1+v1+b2+v2)).then(x=>x.text());if(t.length>5000){const blob=new Blob([t],{type:'text/plain'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='sonuc.txt';a.click();r.textContent='Buyuk veri algilandi. TXT indirildi.';}else{r.textContent=t}}
</script><?php endif; ?></body>
</html>
