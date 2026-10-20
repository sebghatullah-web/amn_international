$ErrorActionPreference = 'Stop'
$base = 'C:\xampp\htdocs\AMN_INTERNATIONAL\assets\vendor'
New-Item -ItemType Directory -Path "$base\css" -Force | Out-Null
New-Item -ItemType Directory -Path "$base\js" -Force | Out-Null

$files = @(
    @{ url = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'; out = "$base\css\bootstrap.min.css" },
    @{ url = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css.map'; out = "$base\css\bootstrap.min.css.map" },
    @{ url = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'; out = "$base\js\bootstrap.bundle.min.js" },
    @{ url = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js.map'; out = "$base\js\bootstrap.bundle.min.js.map" }
)

foreach ($f in $files) {
    try {
        Invoke-WebRequest -Uri $f.url -OutFile $f.out -TimeoutSec 60 -UseBasicParsing
        Write-Host "OK $($f.out)"
    } catch {
        Write-Host "FAIL $($f.url): $($_.Exception.Message)"
    }
}
Write-Host 'DONE'
