Param(
    [switch]$NonInteractive
)

$ErrorActionPreference = 'Stop'

function Set-EnvValue([string]$Key, [string]$Value) {
    $envPath = Join-Path $PSScriptRoot '..' '.env' | Resolve-Path
    $content = Get-Content $envPath -Raw
    $pattern = "(?m)^$Key=.*$"
    $line = "$Key=$Value"
    if ($content -match $pattern) {
        $content = [System.Text.RegularExpressions.Regex]::Replace($content, $pattern, [System.Text.RegularExpressions.Regex]::Escape($line).Replace('\\=', '='))
    } else {
        if (-not $content.EndsWith("`n")) { $content += "`n" }
        $content += $line + "`n"
    }
    Set-Content -Path $envPath -Value $content -Encoding UTF8
}

function Get-EnvValue([string]$Key) {
    $envPath = Join-Path $PSScriptRoot '..' '.env' | Resolve-Path
    if (-not (Test-Path $envPath)) { return $null }
    $line = Select-String -Path $envPath -Pattern "(?m)^$Key=(.*)$" | Select-Object -First 1
    if ($line) { return $line.Matches[0].Groups[1].Value }
    return $null
}

Write-Host '=== WazaElimu Installer ===' -ForegroundColor Cyan

# 1) Auto-detect APP_URL, allow override
$defaultUrl = Get-EnvValue 'APP_URL'
if ([string]::IsNullOrWhiteSpace($defaultUrl)) { $defaultUrl = 'http://localhost' }
$APP_URL = if ($NonInteractive) { $defaultUrl } else { Read-Host "APP_URL [$defaultUrl]" }
if ([string]::IsNullOrWhiteSpace($APP_URL)) { $APP_URL = $defaultUrl }
Set-EnvValue 'APP_URL' $APP_URL
Write-Host "APP_URL set to: $APP_URL" -ForegroundColor Green

# 2) Choose DB type
if ($NonInteractive) {
    $dbType = (Get-EnvValue 'DB_CONNECTION'); if ([string]::IsNullOrWhiteSpace($dbType)) { $dbType = 'mysql' }
} else {
    Write-Host "Choose database type:" -ForegroundColor Yellow
    Write-Host "  1) MySQL"; Write-Host "  2) PostgreSQL"; Write-Host "  3) SQLite"
    $choice = Read-Host 'Enter 1/2/3 [1]'
    switch ($choice) {
        '2' { $dbType = 'pgsql' }
        '3' { $dbType = 'sqlite' }
        default { $dbType = 'mysql' }
    }
}

switch ($dbType) {
    'mysql' {
        $host = if ($NonInteractive) { (Get-EnvValue 'DB_HOST'); if (-not $host) { '127.0.0.1' } else { $host } } else { Read-Host 'MySQL host [127.0.0.1]' }
        if ([string]::IsNullOrWhiteSpace($host)) { $host = '127.0.0.1' }
        $port = if ($NonInteractive) { (Get-EnvValue 'DB_PORT'); if (-not $port) { '3306' } else { $port } } else { Read-Host 'MySQL port [3306]' }
        if ([string]::IsNullOrWhiteSpace($port)) { $port = '3306' }
        $db   = if ($NonInteractive) { (Get-EnvValue 'DB_DATABASE'); if (-not $db) { 'wazaelimu' } else { $db } } else { Read-Host 'MySQL database [wazaelimu]' }
        if ([string]::IsNullOrWhiteSpace($db)) { $db = 'wazaelimu' }
        $user = if ($NonInteractive) { (Get-EnvValue 'DB_USERNAME'); if (-not $user) { 'root' } else { $user } } else { Read-Host 'MySQL username [root]' }
        if ([string]::IsNullOrWhiteSpace($user)) { $user = 'root' }
        if ($NonInteractive) { $pass = (Get-EnvValue 'DB_PASSWORD') } else { $sec = Read-Host 'MySQL password (leave blank if none)' -AsSecureString; $pass = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($sec)) }

        Set-EnvValue 'DB_CONNECTION' 'mysql'
        Set-EnvValue 'DB_HOST' $host
        Set-EnvValue 'DB_PORT' $port
        Set-EnvValue 'DB_DATABASE' $db
        Set-EnvValue 'DB_USERNAME' $user
        Set-EnvValue 'DB_PASSWORD' $pass

        $global:ImportSample = $true
    }
    'pgsql' {
        $host = if ($NonInteractive) { (Get-EnvValue 'DB_HOST'); if (-not $host) { '127.0.0.1' } else { $host } } else { Read-Host 'Postgres host [127.0.0.1]' }
        if ([string]::IsNullOrWhiteSpace($host)) { $host = '127.0.0.1' }
        $port = if ($NonInteractive) { (Get-EnvValue 'DB_PORT'); if (-not $port) { '5432' } else { $port } } else { Read-Host 'Postgres port [5432]' }
        if ([string]::IsNullOrWhiteSpace($port)) { $port = '5432' }
        $db   = if ($NonInteractive) { (Get-EnvValue 'DB_DATABASE'); if (-not $db) { 'wazaelimu' } else { $db } } else { Read-Host 'Postgres database [wazaelimu]' }
        if ([string]::IsNullOrWhiteSpace($db)) { $db = 'wazaelimu' }
        $user = if ($NonInteractive) { (Get-EnvValue 'DB_USERNAME'); if (-not $user) { 'postgres' } else { $user } } else { Read-Host 'Postgres username [postgres]' }
        if ([string]::IsNullOrWhiteSpace($user)) { $user = 'postgres' }
        if ($NonInteractive) { $pass = (Get-EnvValue 'DB_PASSWORD') } else { $sec = Read-Host 'Postgres password (leave blank if none)' -AsSecureString; $pass = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($sec)) }

        Set-EnvValue 'DB_CONNECTION' 'pgsql'
        Set-EnvValue 'DB_HOST' $host
        Set-EnvValue 'DB_PORT' $port
        Set-EnvValue 'DB_DATABASE' $db
        Set-EnvValue 'DB_USERNAME' $user
        Set-EnvValue 'DB_PASSWORD' $pass

        $global:ImportSample = $false
    }
    'sqlite' {
        Set-EnvValue 'DB_CONNECTION' 'sqlite'
        Set-EnvValue 'DB_HOST' ''
        Set-EnvValue 'DB_PORT' ''
        Set-EnvValue 'DB_DATABASE' ''
        Set-EnvValue 'DB_USERNAME' ''
        Set-EnvValue 'DB_PASSWORD' ''
        $global:ImportSample = $false
    }
}

# 3) Migrate?
$doMigrate = if ($NonInteractive) { 'y' } else { Read-Host 'Run migrations now? (y/n) [y]' }
if ([string]::IsNullOrWhiteSpace($doMigrate)) { $doMigrate = 'y' }

$root = Resolve-Path (Join-Path $PSScriptRoot '..')
Push-Location $root
try {
    & php artisan config:clear | Out-Host
    # Generate key if empty
    $appKey = Get-EnvValue 'APP_KEY'
    if ([string]::IsNullOrWhiteSpace($appKey) -or $appKey -eq 'base64:') {
        & php artisan key:generate | Out-Host
    }

    if ($doMigrate -match '^(y|Y)') {
        & php artisan session:table | Out-Host
        & php artisan queue:table | Out-Host
        & php artisan cache:table | Out-Host
        & php artisan migrate | Out-Host

        if ($global:ImportSample -eq $true) {
            $imp = if ($NonInteractive) { 'n' } else { Read-Host 'Import MySQL sample data? (y/n) [n]' }
            if ($imp -match '^(y|Y)') {
                $db = Get-EnvValue 'DB_DATABASE'
                $user = Get-EnvValue 'DB_USERNAME'
                $pass = Get-EnvValue 'DB_PASSWORD'
                $host = Get-EnvValue 'DB_HOST'
                $port = Get-EnvValue 'DB_PORT'

                $sqlPath = Resolve-Path (Join-Path $root 'database/mysql/sample_data.sql')
                if ([string]::IsNullOrWhiteSpace($pass)) {
                    & mysql -u $user -h $host -P $port $db < $sqlPath
                } else {
                    # Using --password can expose in process list; safer to prompt if needed
                    Write-Host 'Please enter your MySQL password to import sample data:' -ForegroundColor Yellow
                    & mysql -u $user -p -h $host -P $port $db < $sqlPath
                }
                Write-Host 'Sample data imported.' -ForegroundColor Green
            }
        }
    } else {
        Write-Host 'Skipped migrations.' -ForegroundColor Yellow
    }
}
finally {
    Pop-Location
}

Write-Host 'Installation complete.' -ForegroundColor Cyan
