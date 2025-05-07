<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS Sorgu </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="blog.css">
</head>
<body>
<?php
session_start();
include "navbar.php";
if ($_SESSION['dns'] != 1) {
    header("Location:index.php");
    exit;
}
?>
<div class="container mt-5">
    <div class="container mt-5 p-4 border rounded shadow" style="max-width: 900px;">
        <h1 class="text-center mb-4">DNS Sorgu Aracı</h1>
        <form id="dnsForm">
            <div class="form-group mb-3">
                <label for="domain">Domain:</label>
                <input type="text" id="domain" name="domain" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="queryType">Sorgu Türü:</label>
                <select id="queryType" name="type" class="form-select" required>
                    <option value="">Seçiniz</option>
                    <option value="A">A</option>
                    <option value="MX">MX</option>
                    <option value="TXT">TXT</option>
                    <option value="SPF">SPF</option>
                    <option value="CNAME">CNAME</option>
                    <option value="AAAA">AAAA</option>
                    <option value="SRV">SRV</option>
                    <option value="SOA">SOA</option>
                    <option value="ALL">ALL</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">Sorgula</button>
            </div>
        </form>
        <div id="result" class="result mt-4 p-3 border rounded bg-light shadow-sm""></div>
</div>
</div>
<?php
include "footer.php";
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script>
    document.getElementById('dnsForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const domain = document.getElementById('domain').value;
        const queryType = document.getElementById('queryType').value;

        fetch('DNSsorgu/DNS_Api.php?type=' + encodeURIComponent(queryType), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'domain=' + encodeURIComponent(domain),
        })
            .then(response => response.json())
            .then(data => {
                let resultDiv = document.getElementById('result');
                if (data.warnings) {
                    resultDiv.innerHTML = '<div class="error">' + data.warnings.join('<br>') + '</div>';
                } else if (data.error) {
                    resultDiv.innerHTML = '<div class="error">' + data.error + '</div>';
                } else if (Array.isArray(data) && data.length > 0) {
                    let resultHtml = '';
                    data.forEach(record => {
                        if (['A', 'MX', 'AAAA', 'SOA'].includes(queryType)) {
                            for (const [key, value] of Object.entries(record)) {
                                resultHtml += `<p><strong>${key}:</strong> ${value || ''}</p>`;
                            }
                        } else {
                            resultHtml += `<p>${JSON.stringify(record, null, 2)}</p>`;
                        }
                        resultHtml += '<hr>'; // Her kayıt arasına bir ayırıcı ekler.
                    });
                    resultDiv.innerHTML = resultHtml;
                } else {
                    resultDiv.innerHTML = 'Sonuç bulunamadı.';
                }
            })
            .catch(error => {
                document.getElementById('result').innerHTML = '<div class="error">Bir hata oluştu: ' + error.message + '</div>';
            });
    });
</script>
</body>
</html>
