@echo off

rem Get input and write to config/input2.df

:loop
set /p input=:
if exist "config\input2.df" del "config\input2.df"
echo %input% >>config/input2.df
goto loop
