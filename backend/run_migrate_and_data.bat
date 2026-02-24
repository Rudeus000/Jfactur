@echo off
REM Ejecutar migraciones y cargar datos de ejemplo (estilo demodb) para que la base no esté vacía.
cd /d "%~dp0"
call venv\Scripts\activate.bat
echo Aplicando migraciones...
python manage.py migrate
if errorlevel 1 exit /b 1
echo.
echo Cargando datos de prueba...
python manage.py load_sample_data
echo.
echo Listo. Base con datos. Login: rudeus@jfactur.local / rudeus123
pause
