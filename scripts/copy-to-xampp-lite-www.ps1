# Copy goi release vao XAMPP-Lite www de chay thu local.
# Mac dinh: chay copy-release.ps1 roi mirror sang C:\xampp_lite_8_3\www
#
# Usage:
#   .\scripts\copy-to-xampp-lite-www.ps1
#   .\scripts\copy-to-xampp-lite-www.ps1 -Destination "D:\xampp\www"
#   .\scripts\copy-to-xampp-lite-www.ps1 -SkipRelease -PreserveEnv

param(
    [string] $Destination = "C:\xampp_lite_8_3\www",
    [string] $Source = "",
    [switch] $SkipRelease,
    [switch] $PreserveEnv
)

$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $PSScriptRoot
$ReleaseDir = [System.IO.Path]::GetFullPath((Join-Path $Root "..\apple-store-web-app-release"))
$Destination = [System.IO.Path]::GetFullPath($Destination)

if (-not $SkipRelease) {
    Write-Host "==> Tao goi release (copy-release.ps1)..."
    & (Join-Path $PSScriptRoot "copy-release.ps1")
    if ($LASTEXITCODE -ne 0) {
        Write-Error "copy-release.ps1 that bai (exit $LASTEXITCODE)"
    }
}

if ([string]::IsNullOrWhiteSpace($Source)) {
    $Source = $ReleaseDir
} else {
    $Source = [System.IO.Path]::GetFullPath($Source)
}

if (-not (Test-Path (Join-Path $Source "vendor\autoload.php"))) {
    Write-Error "Thieu vendor/ trong nguon: $Source"
}
if (-not (Test-Path (Join-Path $Source "artisan"))) {
    Write-Error "Khong phai project Laravel: $Source"
}

Write-Host ""
Write-Host "Nguon:      $Source"
Write-Host "Dich:       $Destination"
Write-Host ""

$envBackup = $null
if ($PreserveEnv -and (Test-Path (Join-Path $Destination ".env"))) {
    $envBackup = Join-Path $env:TEMP ("xampp-www-env-backup-" + [Guid]::NewGuid().ToString() + ".env")
    Copy-Item (Join-Path $Destination ".env") $envBackup -Force
    Write-Host "Giu .env cu tai dich (-PreserveEnv)"
}

New-Item -ItemType Directory -Path $Destination -Force | Out-Null

Write-Host "Copy vao XAMPP www..."
$robocopyExit = 0
robocopy $Source $Destination /MIR /NFL /NDL /NJH /NJS /NC /NS /NP
if ($LASTEXITCODE -ge 8) { $robocopyExit = $LASTEXITCODE }
if ($robocopyExit -ge 8) {
    Write-Error "robocopy that bai (exit $robocopyExit)"
}

if ($envBackup) {
    Copy-Item $envBackup (Join-Path $Destination ".env") -Force
    Remove-Item $envBackup -Force -ErrorAction SilentlyContinue
}

$required = @(
    "vendor\autoload.php",
    ".env",
    "public\build\manifest.json",
    "public\storage",
    "artisan",
    "xampp-lite-conf\httpd.conf.example"
)
$missing = $required | Where-Object { -not (Test-Path (Join-Path $Destination $_)) }
if ($missing.Count -gt 0) {
    Write-Error ("XAMPP www thieu: " + ($missing -join ", "))
}

Write-Host ""
Write-Host "Xong. Mo trinh duyet:"
Write-Host "  http://127.0.0.1:8080"
Write-Host ""
Write-Host "Da kiem tra:"
foreach ($item in $required) {
    Write-Host "  OK $item"
}
Write-Host ""
Write-Host "Lu y: dung 127.0.0.1:8080 (khong dung localhost:8080)."
