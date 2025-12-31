@echo off
REM Export food_court database
echo Exporting food_court database...
C:\xampp1\mysql\bin\mysqldump.exe -u root -P 3307 -h 127.0.0.1 food_court > food_court_backup.sql

REM Create new database and import
echo Creating foodie_hub database...
C:\xampp1\mysql\bin\mysql.exe -u root -P 3307 -h 127.0.0.1 -e "CREATE DATABASE IF NOT EXISTS foodie_hub;"

echo Importing to foodie_hub...
C:\xampp1\mysql\bin\mysql.exe -u root -P 3307 -h 127.0.0.1 foodie_hub < food_court_backup.sql

echo Done! Database copied successfully.
pause
