@echo off
REM MySQL データベース バックアップスクリプト
set DATE=%DATE:~0,4%%DATE:~5,2%%DATE:~8,2%
set TIME_NOW=%TIME:~0,2%%TIME:~3,2%%TIME:~6,2%
set BACKUP_FILE=backup_%DATE%_%TIME_NOW%.sql

echo Creating backup: %BACKUP_FILE%
docker exec casmen-mysql-1 mysqldump -u root -ppassword --default-character-set=utf8mb4 --set-charset --single-transaction laravel > %BACKUP_FILE%

if %ERRORLEVEL% equ 0 (
    echo Backup created successfully: %BACKUP_FILE%
    echo Verifying Japanese characters...
    docker exec casmen-mysql-1 bash -c "mysqldump -u root -ppassword --default-character-set=utf8mb4 --set-charset --single-transaction laravel | grep -o '営業経験について' | head -1"
) else (
    echo Backup failed!
)

pause
