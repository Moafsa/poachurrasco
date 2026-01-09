@echo off
echo Limpando cache do Laravel...
docker exec poachurras_app php artisan view:clear
docker exec poachurras_app php artisan cache:clear
docker exec poachurras_app php artisan config:clear
echo Cache limpo com sucesso!
echo.
echo Verifique se a seção do Selo de Qualidade aparece agora na home.
pause


















