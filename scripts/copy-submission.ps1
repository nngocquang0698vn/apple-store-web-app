# Copy project sang thu muc nop bai (khong gom non-submission, node_modules, .git, ...).
# Tao .env san tu .env.xampp.example (co APP_KEY) de thay co nop zip.
#
# Usage:
#   .\scripts\copy-submission.ps1
#   .\scripts\copy-submission.ps1 -Destination "D:\nop-bai\apple-store-web-app"

param(
    [string] $Destination = (Join-Path (Split-Path -Parent $PSScriptRoot) "..\apple-store-web-app-submission")
)

$ErrorActionPreference = "Stop"
$Source = Split-Path -Parent $PSScriptRoot
$Destination = [System.IO.Path]::GetFullPath($Destination)

Write-Host "Nguon:      $Source"
Write-Host "Dich:       $Destination"
Write-Host ""

if (Test-Path $Destination) {
    Write-Host "Xoa thu muc dich cu..."
    Remove-Item $Destination -Recurse -Force
}

New-Item -ItemType Directory -Path $Destination -Force | Out-Null

if (-not (Test-Path (Join-Path $Source "vendor\autoload.php"))) {
    Write-Error "Thieu vendor/. Chay: composer install --no-dev (hoac composer install) roi chay lai script."
}

Write-Host "Copy file (bo qua non-submission, node_modules, .git)..."
$robocopyExit = 0
robocopy $Source $Destination /MIR /NFL /NDL /NJH /NJS /NC /NS /NP `
    /XD non-submission node_modules .git .idea .vscode .cursor .phpunit.cache `
    /XF .env .env.docker .phpunit.result.cache
if ($LASTEXITCODE -ge 8) { $robocopyExit = $LASTEXITCODE }
if ($robocopyExit -ge 8) {
    Write-Error "robocopy that bai (exit $robocopyExit)"
}

# storage: giu app/, bo logs/cache phien lam viec
Remove-Item (Join-Path $Destination "storage\logs\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\cache\data\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\sessions\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\views\*") -Recurse -Force -ErrorAction SilentlyContinue
New-Item -ItemType Directory -Force -Path (Join-Path $Destination "storage\logs") | Out-Null

# public/storage: copy file that (Windows/XAMPP khong can symlink)
Write-Host "Copy anh vao public/storage..."
$storageSrc = Join-Path $Destination "storage\app\public"
$storageDest = Join-Path $Destination "public\storage"
Remove-Item $storageDest -Recurse -Force -ErrorAction SilentlyContinue
if (Test-Path $storageSrc) {
    Copy-Item $storageSrc $storageDest -Recurse -Force
} else {
    Write-Warning "Thieu storage/app/public - anh demo co the khong hien thi"
}
Remove-Item (Join-Path $Destination "public\hot") -Force -ErrorAction SilentlyContinue

Write-Host "Tao .env cho goi nop (tu .env.xampp.example)..."
$xamppExample = Join-Path $Source ".env.xampp.example"
$destEnv = Join-Path $Destination ".env"
if (-not (Test-Path $xamppExample)) {
    Write-Error "Thieu .env.xampp.example"
}
Copy-Item $xamppExample $destEnv -Force

function Resolve-PhpExecutable {
    $php = Get-Command php -ErrorAction SilentlyContinue
    if ($php) {
        $version = & $php.Source -r "echo PHP_VERSION;" 2>$null
        if ($version -and ([version]$version -ge [version]"8.3.0")) {
            return $php.Source
        }
    }

    $candidates = @(
        "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe"
    )
    $candidates += Get-ChildItem "C:\laragon\bin\php" -Directory -ErrorAction SilentlyContinue |
        Where-Object { $_.Name -like "php-8.3*" } |
        ForEach-Object { Join-Path $_.FullName "php.exe" }

    foreach ($candidate in $candidates | Select-Object -Unique) {
        if (Test-Path $candidate) {
            return $candidate
        }
    }

    return $null
}

$phpExe = Resolve-PhpExecutable
if (-not $phpExe) {
    Write-Error "Khong tim thay PHP 8.3+. Bat Laragon PHP 8.3 hoac them php vao PATH."
}

Push-Location $Destination
& $phpExe artisan key:generate --force | Out-Null
if ($LASTEXITCODE -ne 0) {
    Pop-Location
    Write-Error "php artisan key:generate that bai ($phpExe)"
}
Pop-Location

@'
Huong dan cai dat (thay co chay bai)
====================================

Doc README.md - muc "Huong dan cai dat (danh cho thay co cham bai)".

Tom tat:
  1. Tai XAMPP-Lite 8.3.30 (Windows x64):
     https://sourceforge.net/projects/xampplite/files/8.3/8.3.30/x64/php-man-en/
  2. Copy toan bo thu muc nay vao www/ (file artisan nam ngay trong www/)
  3. Copy 4 file mau Apache (xampp-lite-httpd*.example) vao apps/apache/conf/
  4. Start Apache + MySQL, import database/dumps/apple_store-demo.sql
  5. Mo http://127.0.0.1:8080 (phpMyAdmin: http://localhost/phpmyadmin)

Anh da copy san trong public/storage/ - khong can storage:link.

File .env da co san trong goi - khong can key:generate.

Admin: admin@istore.test / password
'@ | Set-Content -Path (Join-Path $Destination "HUONG-DAN-NOP.txt") -Encoding UTF8

$required = @(
    "vendor\autoload.php",
    ".env",
    "public\build\manifest.json",
    "public\storage",
    "database\dumps\apple_store-demo.sql"
)
$missing = $required | Where-Object { -not (Test-Path (Join-Path $Destination $_)) }
if ($missing.Count -gt 0) {
    Write-Error ("Goi nop thieu: " + ($missing -join ", "))
}

$appKey = (Select-String -Path (Join-Path $Destination ".env") -Pattern "^APP_KEY=(.+)$").Matches.Groups[1].Value.Trim()
if ([string]::IsNullOrWhiteSpace($appKey)) {
    Write-Error ".env thieu APP_KEY — php artisan key:generate that bai."
}

Write-Host ""
Write-Host "Xong. Thu muc san sang zip:"
Write-Host "  $Destination"
Write-Host ""
Write-Host "Da kiem tra:"
foreach ($item in $required) {
    Write-Host "  OK $item"
}
Write-Host "  OK APP_KEY"
