# View last 50 log lines
Get-Content storage\logs\laravel.log -Tail 50

# Watch logs in real-time
Get-Content storage\logs\laravel.log -Wait -Tail 20

# Search for errors
Select-String -Path storage\logs\laravel.log -Pattern "ERROR" | Select-Object -Last 10

# Search for OTP/emails
Get-Content storage\logs\laravel.log | Select-String -Pattern "OTP|email" | Select-Object -Last 5

# View today's log file
Get-Content "storage\logs\laravel-$(Get-Date -Format 'yyyy-MM-dd').log" -Tail 50

# Clear all logs
Remove-Item storage\logs\*.log
