<?php
?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>DJI SRT Sanitizer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h3 class="mb-4 text-center">
DJI SRT Sanitizer
</h3>

<form action="select_vars.php" method="post" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">
Subir archivo SRT
</label>

<input
type="file"
name="srt"
class="form-control"
required
accept=".srt">

</div>

<div class="d-grid">

<button class="btn btn-primary">
Procesar archivo
</button>

</div>

</form>

</div>
</div>

</div>
</div>

</div>

</body>
</html>