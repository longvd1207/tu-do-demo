@echo off

REM Get Computer Name
set "computer_name=%COMPUTERNAME%"

REM Get CPU ID
for /f "delims=" %%a in ('wmic cpu get ProcessorId ^| findstr /r "[A-Za-z0-9]"') do set "cpu_id=%%a"

REM Get Motherboard ID
for /f "skip=1 tokens=2 delims==" %%b in ('wmic baseboard get SerialNumber /value') do set "motherboard_id=%%b"

setlocal enabledelayedexpansion

REM Initialize IP and MAC address variables
set "ip_addresses="
set "mac_addresses="

for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /c:"IPv4 Address"') do (
    set "ip=!ip_addresses!, %%i"
    set "ip_addresses=!ip_addresses!, %%i"
)

for /f "tokens=2 delims=:" %%i in ('ipconfig /all ^| findstr /c:"Physical Address"') do (
    set "mac=!mac_addresses!, %%i"
    set "mac_addresses=!mac_addresses!, %%i"
)

REM Remove leading commas and spaces
set "ip_addresses=%ip_addresses:~2%"
set "mac_addresses=%mac_addresses:~2%"

REM Export the retrieved information to a text file
(
    echo Computer Name: %computer_name%
    echo CPU ID: %cpu_id%
    echo Motherboard ID: %motherboard_id%
    echo IP Addresses: %ip_addresses%
    echo MAC Addresses: %mac_addresses%
) > output.txt