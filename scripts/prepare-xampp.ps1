# Chuẩn bị gói cài XAMPP-Lite: build frontend, seed DB, export SQL.
# Chạy trên máy dev (PHP 8.3+) trước khi nộp hoặc zip project.
#
# Usage (PowerShell, từ thư mục gốc project):
#   .\scripts\prepare-xampp.ps1
#   .\scripts\prepare-xampp.ps1 -SkipNpm        # bỏ qua npm ci/build
#   .\scripts\prepare-xampp.ps1 -SkipMigrate   # chỉ export DB hiện tại

param(
    [switch] $SkipNpm,
    [switch] $SkipMigrate
)

$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $PSScriptRoot
Set-Location $Root

function Read-DotEnvValue {
    param([string] $Key, [string] $Default = "")
    $envPath = Join-Path $Root ".env"
    if (-not (Test-Path $envPath)) {
        return $Default
    }
    foreach ($line in Get-Content $envPath) {
        if ($line -match "^$Key=(.*)$") {
            return $matches[1].Trim().Trim('"').Trim("'")
        }
    }
    return $Default
}

Write-Host "==> Kiem tra PHP / Composer"
php --version | Out-Null
composer --version | Out-Null

if (-not (Test-Path (Join-Path $Root ".env"))) {
    Write-Host "==> Tao .env tu .env.example"
    Copy-Item (Join-Path $Root ".env.example") (Join-Path $Root ".env")
    php artisan key:generate --force
}

if (-not $SkipNpm) {
    Write-Host "==> npm ci && npm run build"
    npm ci
    npm run build
} else {
    Write-Host "==> Bo qua npm (SkipNpm)"
}

if (-not $SkipMigrate) {
    Write-Host "==> php artisan migrate:fresh --seed"
    php artisan migrate:fresh --seed --force
} else {
    Write-Host "==> Bo qua migrate (SkipMigrate)"
}

Write-Host "==> php artisan storage:link"
if (Test-Path (Join-Path $Root "public\storage")) {
    Remove-Item (Join-Path $Root "public\storage") -Force -Recurse -ErrorAction SilentlyContinue
}
php artisan storage:link

Write-Host "==> Export MySQL dump"
$dumpDir = Join-Path $Root "database\dumps"
New-Item -ItemType Directory -Force -Path $dumpDir | Out-Null

$dbHost = Read-DotEnvValue "DB_HOST" "127.0.0.1"
$dbPort = Read-DotEnvValue "DB_PORT" "3306"
$dbName = Read-DotEnvValue "DB_DATABASE" "apple_store"
$dbUser = Read-DotEnvValue "DB_USERNAME" "root"
$dbPass = Read-DotEnvValue "DB_PASSWORD" ""

$dumpFile = Join-Path $dumpDir "apple_store-demo.sql"
$mysqldump = Get-Command mysqldump -ErrorAction SilentlyContinue
if (-not $mysqldump) {
    $candidates = @(
        "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe",
        "C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe",
        "C:\xampp\mysql\bin\mysqldump.exe"
    )
    foreach ($path in $candidates) {
        if (Test-Path $path) {
            $mysqldump = $path
            break
        }
    }
}

if (-not $mysqldump) {
    Write-Warning "Khong tim thay mysqldump. Export SQL thu cong qua phpMyAdmin: database/dumps/apple_store-demo.sql"
} else {
    $exe = if ($mysqldump -is [string]) { $mysqldump } else { $mysqldump.Source }
    $args = @("-h", $dbHost, "-P", $dbPort, "-u", $dbUser)
    if ($dbPass) {
        $args += "-p$dbPass"
    }
    $args += @(
        "--routines",
        "--triggers",
        "--single-transaction",
        "--default-character-set=utf8mb4",
        "--set-charset",
        "--column-statistics=0",
        "--databases",
        "--add-drop-database",
        "--result-file=$dumpFile",
        $dbName
    )

    & $exe @args
    if ($LASTEXITCODE -ne 0) {
        Write-Error "mysqldump that bai (exit $LASTEXITCODE)"
    }

    Write-Host "   Da luu: database/dumps/apple_store-demo.sql (utf8mb4, co CREATE DATABASE)"
}

Write-Host ""
Write-Host "Xong. Buoc tiep theo:"
Write-Host "  1. Chay copy-submission.ps1 de tao goi co .env san"
Write-Host "  2. Doc README.md (muc huong dan cho thay co)"
Write-Host "  3. Zip/nop ca thu muc project (co vendor/, public/build/, database/dumps/)"
