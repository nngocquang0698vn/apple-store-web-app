# Copy project sang thu muc nop bai (khong gom non-submission, node_modules, .git, ...).
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

Write-Host "Copy file (bo qua non-submission, node_modules, .git)..."
$robocopyExit = 0
robocopy $Source $Destination /MIR /NFL /NDL /NJH /NJS /NC /NS /NP `
    /XD non-submission node_modules .git .idea .vscode .cursor .phpunit.cache `
    /XF .env .env.docker .phpunit.result.cache
if ($LASTEXITCODE -ge 8) { $robocopyExit = $LASTEXITCODE }

# storage: giu app/, bo logs/cache phien lam viec
Remove-Item (Join-Path $Destination "storage\logs\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\cache\data\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\sessions\*") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "storage\framework\views\*") -Recurse -Force -ErrorAction SilentlyContinue
New-Item -ItemType Directory -Force -Path (Join-Path $Destination "storage\logs") | Out-Null

# public/storage la symlink — nguoi cai chay storage:link
Remove-Item (Join-Path $Destination "public\storage") -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item (Join-Path $Destination "public\hot") -Force -ErrorAction SilentlyContinue

@'
Goi nop iStore
==============

Cai XAMPP: doc XAMPP.md

Tom tat:
  1. Copy thu muc nay vao C:\xampp\htdocs\apple-store-web-app
  2. Copy .env.xampp.example -> .env
  3. C:\xampp\php\php.exe artisan key:generate
  4. Import database/dumps/apple_store-demo.sql (phpMyAdmin)
  5. C:\xampp\php\php.exe artisan storage:link
  6. Mo http://apple-store.local (xem XAMPP.md)

Admin: admin@istore.test / password
'@ | Set-Content -Path (Join-Path $Destination "HUONG-DAN-NOP.txt") -Encoding UTF8

if ($robocopyExit -ge 8) {
    Write-Error "robocopy that bai (exit $robocopyExit)"
}

Write-Host ""
Write-Host "Xong. Thu muc san sang zip:"
Write-Host "  $Destination"
Write-Host ""
Write-Host "Kiem tra nhanh:"
Write-Host "  vendor/autoload.php"
Write-Host "  public/build/manifest.json"
Write-Host "  database/dumps/apple_store-demo.sql"
