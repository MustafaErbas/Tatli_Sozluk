<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WHOIS Lookup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="blog.css">
</head>
<body>
<?php
session_start();
include "navbar.php";
if ($_SESSION['whois'] != 1) {
    header("Location:index.php");
    exit;
}
?>
<div class="container mt-5">
    <div class="box container mt-5 p-5 shadow-lg rounded" style="max-width: 900px;">
        <h1 class="text-center mb-4">WHOIS Lookup</h1>
        <form id="whoisForm" method="post" class="d-flex flex-column align-items-center sticky-top bg-white pt-2 pb-2">
            <label class="w-100">
                <input type="text" id="domain" name="domain" class="form-control mb-3" placeholder="Enter domain name">
            </label>
            <input type="submit" value="Lookup" class="btn btn-success w-100">
        </form>
        <div id="result" class="mt-4 bg-light rounded p-4 shadow-sm"></div>
    </div>
</div>

<?php
include "footer.php";
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#whoisForm').on('submit', function(e) {
            e.preventDefault();
            let domain = $('#domain').val();
            let domainRegex = /^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!domainRegex.test(domain)) {
                $('#result').html('Invalid domain format.');
                return;
            }
            $.ajax({
                url: 'WhoIsApisorgu/WhoIsInfoApi.php',
                method: 'POST',
                data: { domain: domain },
                success: function(response) {
                    let data = (typeof response === 'string') ? JSON.parse(response) : response;
                    let html = formatData(data);
                    $('#result').html(html);
                },
                error: function(xhr, status, error) {
                    $('#result').html('Error: ' + error);
                }
            });
        });

        function formatData(data) {
            let html = '<ul>';
            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    html += '<li><strong>' + key + ':</strong> ';
                    if (typeof data[key] === 'object' && !Array.isArray(data[key])) {
                        html += formatData(data[key]);
                    } else if (Array.isArray(data[key])) {
                        html += '<ul>';
                        data[key].forEach(function(item) {
                            html += '<li>' + (typeof item === 'object' ? formatData(item) : item) + '</li>';
                        });
                        html += '</ul>';
                    } else {
                        html += data[key];
                    }
                    html += '</li>';
                }
            }
            html += '</ul>';
            return html;
        }
    });
</script>
</body>
</html>