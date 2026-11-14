<#
.SYNOPSIS
    Create an archive of files changed between a tag/commit and HEAD.

.DESCRIPTION
    Windows/PowerShell counterpart of `make changed-archive`.
    File contents are taken from HEAD, never from the working tree.
    Deleted files are skipped because there is no blob for them at HEAD.

    Requires git and tar (bsdtar, shipped with Windows 10 1803 and newer).

.PARAMETER From
    Tag, branch or commit hash to compare against.

.PARAMETER Format
    Archive format: tar.gz (default) or zip. Chosen by bsdtar -a.

.PARAMETER Output
    Archive path. Defaults to phpwcms-<From>-to-HEAD.<Format>.

.EXAMPLE
    .\scripts\changed-archive.ps1 -From v2.0.0-beta.3

.EXAMPLE
    .\scripts\changed-archive.ps1 -From v2.0.0-beta.3 -Format zip
#>
param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string] $From,

    [ValidateSet('tar.gz', 'zip')]
    [string] $Format = 'tar.gz',

    [string] $Output
)

$ErrorActionPreference = 'Stop'

# git emits raw UTF-8 paths with -z; decode native output as UTF-8
$previousEncoding = [Console]::OutputEncoding
[Console]::OutputEncoding = New-Object System.Text.UTF8Encoding $false

$repoRoot = git rev-parse --show-toplevel
if ($LASTEXITCODE -ne 0 -or -not $repoRoot) {
    Write-Error 'Not inside a git repository'
    exit 1
}

git rev-parse --verify --quiet "$From^{commit}" *> $null
if ($LASTEXITCODE -ne 0) {
    Write-Error "Unknown revision: $From"
    exit 1
}

if (-not $Output) {
    $Output = "phpwcms-$($From -replace '/', '-')-to-HEAD.$Format"
}
$outputPath = if ([IO.Path]::IsPathRooted($Output)) { $Output } else { Join-Path $repoRoot $Output }

# NUL separated list keeps paths with spaces intact
$raw = git -C $repoRoot diff -z --name-only --diff-filter=ACMR $From HEAD
if ($LASTEXITCODE -ne 0) {
    exit 1
}
$files = @(($raw -join "`n") -split "`0" | Where-Object { $_ -ne '' })
if ($files.Count -eq 0) {
    Write-Error "No changed files between $From and HEAD"
    exit 1
}

$tmpDir = Join-Path ([IO.Path]::GetTempPath()) ([guid]::NewGuid().ToString())
$listFile = Join-Path ([IO.Path]::GetTempPath()) ("$([guid]::NewGuid()).lst")

try {
    New-Item -ItemType Directory -Path $tmpDir | Out-Null

    $utf8 = New-Object System.Text.UTF8Encoding $false
    [IO.File]::WriteAllText($listFile, ($files -join "`0"), $utf8)

    # materialise HEAD content of the changed files outside the working tree
    $checkout = Start-Process -FilePath 'git' -NoNewWindow -Wait -PassThru `
        -ArgumentList @('-C', "`"$repoRoot`"", '--work-tree', "`"$tmpDir`"", 'checkout-index', '-f', '-z', '--stdin') `
        -RedirectStandardInput $listFile
    if ($checkout.ExitCode -ne 0) {
        Write-Error 'git checkout-index failed'
        exit 1
    }

    tar --null -T $listFile -C $tmpDir -a -cf $outputPath
    if ($LASTEXITCODE -ne 0) {
        Write-Error "tar failed, archive not created: $Output"
        exit 1
    }

    Write-Host "Created $Output ($($files.Count) files)"
}
finally {
    [Console]::OutputEncoding = $previousEncoding
    if (Test-Path $tmpDir) { Remove-Item $tmpDir -Recurse -Force }
    if (Test-Path $listFile) { Remove-Item $listFile -Force }
}
