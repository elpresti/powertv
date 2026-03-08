<?php

require_once "../dji_srt_lib.php";

session_start();

$tmp = $_FILES['srt']['tmp_name'];
$filename = $_FILES['srt']['name'];

$blocks = dji_read_srt($tmp);

$srtReduced = dji_reduce_to_1s($blocks);

$vars = dji_detect_vars($srtReduced);

$_SESSION['srt'] = $srtReduced;
$_SESSION['vars'] = $vars;
$_SESSION['filename'] = $filename;

$firstBlock = explode("\n\n", trim($srtReduced))[0];
$lines = explode("\n", $firstBlock);
$previewData = implode(" ", array_slice($lines,2));

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Elegir variables</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-9">

<div class="card shadow">

<div class="card-body">

<h4 class="mb-4">

Elegir variables para procesar
<strong><?= htmlspecialchars($filename) ?></strong>

</h4>

<form action="process.php" method="post">

<table class="table table-bordered">

<thead class="table-light">

<tr>

<th style="width:80px">Usar</th>
<th>Variable</th>
<th>Prefijo</th>
<th>Sufijo</th>

</tr>

</thead>

<tbody>

<?php foreach ($vars as $name => $regex) { ?>

<tr>

<td class="text-center">

<input
type="checkbox"
class="form-check-input var-check"
data-var="<?= $name ?>"
name="vars[]"
value="<?= $name ?>">

</td>

<td>
<?= $name ?>
</td>

<td>

<input
class="form-control prefix-input"
data-var="<?= $name ?>"
name="prefix_<?= $name ?>">

</td>

<td>

<input
class="form-control suffix-input"
data-var="<?= $name ?>"
name="suffix_<?= $name ?>">

</td>

</tr>

<?php } ?>

</tbody>

</table>

<div class="alert alert-secondary">

<strong>Vista previa del subtítulo</strong>

<div
id="preview"
style="font-size:22px; margin-top:10px">

-

</div>

</div>

<div class="d-grid">

<button class="btn btn-success">
Generar SRT limpio
</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<script>

const rawData = <?= json_encode($previewData) ?>;

function extractValue(variable)
{

if(variable === "FrameCnt")
{
let m = rawData.match(/FrameCnt:\s*([0-9]+)/);
return m ? m[1] : "";
}

if(variable === "DiffTime")
{
let m = rawData.match(/DiffTime:\s*([0-9]+)/);
return m ? m[1] : "";
}

if(variable === "timestamp")
{
let m = rawData.match(/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\./);
return m ? m[1] : "";
}

let regex = new RegExp("\\["+variable+":\\s*([^\\]\\s]+)");
let m = rawData.match(regex);

if(!m) return "";

let v = m[1];

if(variable === "rel_alt")
{
if(v.includes("."))
{
let p = v.split(".");
v = p[0]+"."+p[1].substring(0,1);
}
}

return v;

}

function updatePreview()
{

let previewParts = [];

document.querySelectorAll(".var-check").forEach(chk => {

if(!chk.checked) return;

let variable = chk.dataset.var;

let prefix = document.querySelector(".prefix-input[data-var='"+variable+"']").value;
let suffix = document.querySelector(".suffix-input[data-var='"+variable+"']").value;

let value = extractValue(variable);

previewParts.push(prefix + value + suffix);

});

let text = previewParts.join(" ");

document.getElementById("preview").innerText = text || "-";

}

document.querySelectorAll(".var-check").forEach(el =>
el.addEventListener("change", updatePreview)
);

document.querySelectorAll(".prefix-input").forEach(el =>
el.addEventListener("input", updatePreview)
);

document.querySelectorAll(".suffix-input").forEach(el =>
el.addEventListener("input", updatePreview)
);

</script>

</body>
</html>