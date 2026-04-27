Get-ChildItem -Recurse -File -Path "resources/js","app/Http","app/Models" -ErrorAction SilentlyContinue | ForEach-Object {
    $bytes = [System.IO.File]::ReadAllBytes($_.FullName)
    if($bytes.Length -gt 2 -and $bytes[0] -eq 0 -and $bytes[1] -eq 0 -and $bytes[2] -eq 0) {
        Write-Output $_.FullName
    }
}
