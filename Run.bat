@echo off
set phppath=C:\php\
goto input

:input
set /p input=Run input? [y/n]%input%
if %input%==y start input.bat Input
%phppath%php.exe main.php batch
if %input%==Y( start input.bat Input
%phppath%php.exe main.php batch goto loop)else(
goto loop )

echo Launching bot...
echo.
%phppath%php.exe main.php batch 
echo.
echo --------------------------------------------------------------------------------
echo If this fails first time you use it, then you need to edit this file and change
echo the phppath variable to the path of your php.exe program. For example, if your
echo php.exe is "C:\php\php.exe", then you need to edit the second line of this file
echo to read "set phppath=C:\php\"
echo --------------------------------------------------------------------------------
echo.
goto loop

:loop
if exist config/restart.txt (
echo Restart = YES
set input=y
) else (
set /p input=Run the bot again? [y/n]%input%
)
if %input%==y goto continue
if %input%==Y (
goto continue
) else (
goto stop
)

:continue
echo Launching bot again...
echo.
%phppath%php.exe main.php batch
echo.
goto loop

:stop
pause
