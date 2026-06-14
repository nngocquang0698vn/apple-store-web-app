# Copy source goc de nop bai (khong gom vendor, node_modules, .git, build artifacts, ...).
# Giu nguyen .env tren may dev. Khong tao public/storage - chi co storage/app/public.
#
# Usage:
#   .\scripts\copy-source.ps1
#   .\scripts\copy-source.ps1 -Destination "D:\nop-bai\apple-store-web-app-source"

param(
    [string] $Destination = (Join-Path (Split-Path -Parent $PSScriptRoot) "..\apple-store-web-app-source")
)

$ErrorActionPreference = "Stop"
$Source = Split-Path -Parent $PSScriptRoot
$Destination = [System.IO.Path]::GetFullPath($Destination)

Write-Host "Nguon:      $Source"
Write-Host "Dich:       $Destination"
Write-Host ""

if (Test-Path $Destination) {
    Write-Host "Xoa thu muc dich cu..."
    for ($attempt = 1; $attempt -le 3; $attempt++) {
        try {
            Remove-Item $Destination -Recurse -Force -ErrorAction Stop
            break
        } catch {
            if ($attempt -eq 3) {
                Write-Error "Khong xoa duoc $Destination (thu muc dang bi khoa - dong terminal/Cursor dang mo thu muc do roi chay lai)."
            }
            Start-Sleep -Seconds 2
        }
    }
}

New-Item -ItemType Directory -Path $Destination -Force | Out-Null

Write-Host "Copy source (bo qua vendor, node_modules, .git, public/build; khong theo symlink public/storage)..."
$robocopyExit = 0
robocopy $Source $Destination /MIR /XJ /NFL /NDL /NJH /NJS /NC /NS /NP `
    /XD vendor node_modules non-submission .git .idea .vscode .cursor .phpunit.cache public\storage public\build `
    /XF .phpunit.result.cache
if ($LASTEXITCODE -ge 8) { $robocopyExit = $LASTEXITCODE }
if ($robocopyExit -ge 8) {
    Write-Error "robocopy that bai (exit $robocopyExit)"
}

$publicStorage = Join-Path $Destination "public\storage"
if (Test-Path $publicStorage) {
    Write-Host "Xoa public/storage (goi source khong can - dung storage:link sau khi cai)..."
    Remove-Item $publicStorage -Recurse -Force
}

Remove-Item (Join-Path $Destination "storage\logs\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\cache\data\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\sessions\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\views\*") -Recurse -Force -ErrorAction SilentlyContinue
New-Item -ItemType Directory -Force -Path (Join-Path $Destination "storage\logs") | Out-Null

Remove-Item (Join-Path $Destination "public\hot") -Force -ErrorAction SilentlyContinue

Get-ChildItem (Join-Path $Destination "bootstrap\cache\*.php") -ErrorAction SilentlyContinue |
    Remove-Item -Force -ErrorAction SilentlyContinue

if (Test-Path $publicStorage) {
    Write-Error "Goi source khong duoc co public/storage (hay chay lai script)."
}

@'
Huong dan source code (thay co / giao vien)
==========================================

Day la ban source goc - KHONG co vendor/ va public/build/.

De cai dat va chay (xem README-DEV.md):
  1. composer install
  2. npm ci && npm run build
  3. Copy .env.example hoac .env.xampp.example thanh .env (neu can)
  4. php artisan key:generate
  5. php artisan migrate --seed   (hoac import database/dumps/apple_store-demo.sql)
  6. php artisan storage:link     (Laragon) hoac copy storage/app/public -> public/storage (XAMPP)

Ban release san sang chay: apple-store-web-app-release.zip (xem README.md).
'@ | Set-Content -Path (Join-Path $Destination "HUONG-DAN-SOURCE.txt") -Encoding UTF8

$required = @(
    "composer.json",
    "composer.lock",
    "package.json",
    "artisan",
    "database\dumps\apple_store-demo.sql",
    "xampp-lite-conf\httpd.conf.example",
    "xampp-lite-conf\httpd-vhosts.conf.example",
    "xampp-lite-conf\httpd-xampp-lite-aliases.conf.example",
    "xampp-lite-conf\httpd-xampp-lite.conf.example"
)
$missing = $required | Where-Object { -not (Test-Path (Join-Path $Destination $_)) }
if ($missing.Count -gt 0) {
    Write-Error ("Goi source thieu: " + ($missing -join ", "))
}

Write-Host ""
Write-Host "Xong. Thu muc source:"
Write-Host "  $Destination"
Write-Host ""
Write-Host "Buoc tiep theo:"
Write-Host "  Nen thu muc thanh apple-store-web-app-source.zip (Explorador Windows / 7-Zip) roi nop bai"
Write-Host ""
Write-Host "Da kiem tra:"
foreach ($item in $required) {
    Write-Host "  OK $item"
}
Write-Host "  OK (khong co vendor/ - dung y)"
