# Copy cau hinh Apache mau (xampp-lite-conf) vao XAMPP-Lite.
# Tuong duong "Cach A" trong README.md - Buoc 3.
#
# Usage:
#   .\scripts\copy-xampp-lite-apache-conf.ps1
#   .\scripts\copy-xampp-lite-apache-conf.ps1 -XamppRoot "C:\xampp_lite_8_3" -Verbose
#   .\scripts\copy-xampp-lite-apache-conf.ps1 -XamppRoot "D:\xampp" -ConfigSource "D:\xampp\www\xampp-lite-conf" -Verbose
#   .\scripts\copy-xampp-lite-apache-conf.ps1 -SkipBackup

[CmdletBinding()]
param(
    [string] $XamppRoot = "C:\xampp_lite_8_3",
    [string] $ConfigSource = "",
    [switch] $SkipBackup
)

$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $PSScriptRoot
$XamppRoot = [System.IO.Path]::GetFullPath($XamppRoot)

$maps = @(
    @{ Source = "httpd.conf.example"; Dest = "apps\apache\conf\httpd.conf" },
    @{ Source = "httpd-vhosts.conf.example"; Dest = "apps\apache\conf\extra\httpd-vhosts.conf" },
    @{ Source = "httpd-xampp-lite-aliases.conf.example"; Dest = "apps\apache\conf\extra\httpd-xampp-lite-aliases.conf" },
    @{ Source = "httpd-xampp-lite.conf.example"; Dest = "apps\apache\conf\extra\httpd-xampp-lite.conf" }
)

Write-Verbose "Project root: $Root"
Write-Verbose "XamppRoot (resolved): $XamppRoot"

if ([string]::IsNullOrWhiteSpace($ConfigSource)) {
    $candidates = @(
        (Join-Path $XamppRoot "www\xampp-lite-conf"),
        (Join-Path $Root "xampp-lite-conf")
    )

    Write-Verbose "Tim xampp-lite-conf trong cac thu muc:"
    foreach ($candidate in $candidates) {
        Write-Verbose "  - $candidate"
        if (Test-Path (Join-Path $candidate "httpd.conf.example")) {
            $ConfigSource = $candidate
            Write-Verbose "  => chon: $ConfigSource"
            break
        }
        Write-Verbose "  => khong co httpd.conf.example"
    }
} else {
    $ConfigSource = [System.IO.Path]::GetFullPath($ConfigSource)
    Write-Verbose "ConfigSource (chi dinh): $ConfigSource"
}

if ([string]::IsNullOrWhiteSpace($ConfigSource) -or -not (Test-Path $ConfigSource)) {
    Write-Error "Khong tim thay xampp-lite-conf (can co httpd.conf.example). Truyen -ConfigSource hoac copy project vao www/ truoc."
}

$apacheConf = Join-Path $XamppRoot "apps\apache\conf"
$apacheExtra = Join-Path $XamppRoot "apps\apache\conf\extra"

if (-not (Test-Path $apacheConf)) {
    Write-Error "Khong tim thay $apacheConf - kiem tra -XamppRoot ($XamppRoot)."
}

New-Item -ItemType Directory -Path $apacheExtra -Force | Out-Null
Write-Verbose "Apache conf: $apacheConf"
Write-Verbose "Apache extra: $apacheExtra"

Write-Host "Nguon mau:   $ConfigSource"
Write-Host "XAMPP-Lite:  $XamppRoot"
Write-Host ""
Write-Host "Luu y: Stop Apache trong XAMPP-Lite truoc khi copy."
Write-Host ""

foreach ($map in $maps) {
    $src = Join-Path $ConfigSource $map.Source
    if (-not (Test-Path $src)) {
        Write-Error "Thieu file mau: $src"
    }
    Write-Verbose "Tim thay mau: $src"
}

if (-not $SkipBackup) {
    $backupDir = Join-Path $XamppRoot ("tmp\apache-conf-backup-" + (Get-Date -Format "yyyyMMdd-HHmmss"))
    New-Item -ItemType Directory -Path $backupDir -Force | Out-Null
    Write-Host "Sao luu file cu -> $backupDir"
    Write-Verbose "Backup directory: $backupDir"

    foreach ($map in $maps) {
        $dest = Join-Path $XamppRoot $map.Dest
        if (Test-Path $dest) {
            $backupName = ($map.Dest -replace '[\\/]', '_')
            $backupPath = Join-Path $backupDir $backupName
            Copy-Item $dest $backupPath -Force
            Write-Verbose "Backup: $dest -> $backupPath"
        } else {
            Write-Verbose "Khong co file cu de backup: $dest"
        }
    }

    Write-Host ""
} else {
    Write-Verbose "Bo qua backup (-SkipBackup)"
}

foreach ($map in $maps) {
    $src = Join-Path $ConfigSource $map.Source
    $dest = Join-Path $XamppRoot $map.Dest
    Copy-Item $src $dest -Force
    Write-Host "OK $($map.Source) -> $($map.Dest)"
    Write-Verbose "Copy: $src -> $dest"
}

Write-Host ""
Write-Host "Xong. Buoc tiep theo:"
Write-Host "  1. Start Apache trong XAMPP-Lite"
Write-Host "  2. Mo http://127.0.0.1:8080 (khong dung localhost:8080)"
Write-Host "  3. phpMyAdmin: http://localhost/phpmyadmin"
Write-Host ""
Write-Host "Neu Apache khong start: xem tmp\apache_logs\apache_error.log"
