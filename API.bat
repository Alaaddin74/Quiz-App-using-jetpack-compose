@echo off
setlocal
for /f "tokens=14 delims= " %%i in ('ipconfig ^| findstr /i "IPv4"') do set IPAddress=%%i
%IPAddress%
start http://%IPAddress%/quiz/
endlocal
exit
