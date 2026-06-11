# Wrapper gọi `podman compose` hoặc `docker compose` (ưu tiên Podman nếu có).
param(
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$Args
)

$Root = Split-Path -Parent $PSScriptRoot
$ComposeFile = if (Test-Path "$Root\compose.yaml") { "$Root\compose.yaml" } else { "$Root\docker-compose.yml" }

function Invoke-Compose {
    param(
        [string]$Runtime,
        [string[]]$ComposeArgs
    )

    if ($Runtime -eq "podman") {
        & podman compose -f $ComposeFile @ComposeArgs
        exit $LASTEXITCODE
    }

    & docker compose -f $ComposeFile @ComposeArgs
    exit $LASTEXITCODE
}

if (Get-Command podman -ErrorAction SilentlyContinue) {
    $podmanCompose = podman compose version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Invoke-Compose -Runtime podman -ComposeArgs $Args
    }
}

if (Get-Command docker -ErrorAction SilentlyContinue) {
    $dockerCompose = docker compose version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Invoke-Compose -Runtime docker -ComposeArgs $Args
    }
}

Write-Error "Không tìm thấy Podman Compose hoặc Docker Compose. Cài Podman hoặc Docker Desktop rồi thử lại."
exit 1
