function Show-Menu {
    Write-Host "Выберите действие:"
    Write-Host "1. Архивация"
    Write-Host "2. Сортировка файлов"
    Write-Host "3. Развертывание сайта"
}

function Archive-Files {
    # Укажите путь к папке назначения
    $destinationFolder = Read-Host "Введите путь к папке, которую нужно архивировать"

    # Укажите путь к папке, где будут храниться архивы
    $archiveFolder = Read-Host "Введите путь к папке, где будут храниться архивы"

    # Получаем текущую дату и время для использования в имени архива
    $currentDateTime = Get-Date -Format "yyyyMMdd_HHmmss"

    # Имя архива с добавлением временной метки
    $archiveFileName = "Archive_$currentDateTime.zip"

    # Путь к временному каталогу
    $tempFolder = Join-Path -Path $env:TEMP -ChildPath "TempArchive_$currentDateTime"

    # Путь к архивному файлу
    $archiveFilePath = Join-Path -Path $archiveFolder -ChildPath $archiveFileName

    # Получаем список файлов в папке назначения
    $files = Get-ChildItem -Path $destinationFolder -File

    # Создаем временный каталог
    New-Item -Path $tempFolder -ItemType Directory -Force | Out-Null

    foreach ($file in $files) {
        # Получаем дату последнего редактирования файла
        $lastWriteDate = $file.LastWriteTime
        $lastAccessDate = $file.LastAccessTime
        $currentDate = Get-Date

        # Проверяем, прошло ли более двух недель (14 минут) с момента последнего редактирования файла
        $elapsedWriteTime = $currentDate - $lastWriteDate
        $elapsedAccessTime = $currentDate - $lastAccessDate

        if ($elapsedWriteTime.TotalMinutes -ge 14 -and $elapsedAccessTime.TotalMinutes -ge 14) {
            # Копируем файл во временный каталог
            Copy-Item -Path $file.FullName -Destination $tempFolder -Force
            Write-Output "Файл $($file.Name) был скопирован во временный каталог."

            # Удаляем файл из исходной папки
            Remove-Item -Path $file.FullName -Force
        }
    }

    # Архивируем временный каталог
    Compress-Archive -Path $tempFolder -DestinationPath $archiveFilePath

    # Удаляем временный каталог
    Remove-Item -Path $tempFolder -Force -Recurse

    Write-Output "Архивация завершена. Создан архив: $archiveFilePath"

}

function Sort-Files {
    # Укажите путь к папке назначения
    $destinationFolder = Read-Host "Введите путь к папке, которую нужно сортировать"

    # Проверяем, существуют ли папка eng, если нет - создаем
    if (-not (Test-Path "$destinationFolder\eng")) {
        New-Item -Path "$destinationFolder\eng" -ItemType Directory
    }

    # Получаем список текстовых файлов в папке назначения
    $textFiles = Get-ChildItem -Path $destinationFolder -Filter "*.txt" -File

    foreach ($file in $textFiles) {
        $content = Get-Content $file.FullName -Raw

        # Проверяем содержимое файла на наличие русских и английских символов
        $containsEnglish = $content -match "[a-zA-Z]"
        $containsRussian = $content -match "[А-Яа-яЁё]"

        if ($containsEnglish -and -not $containsRussian) {
            # Перемещаем файл в папку eng, если содержит только английские символы
            Move-Item $file.FullName "$destinationFolder\eng"
        } else {
            # Если содержит смесь символов или нет ни одного из них, выводим предупреждение
            Write-Warning "Файл $($file.Name) содержит смешанные или отсутствующие символы."
        }
    }
}

function Deploy-Site {
    #Установка Chocolatey
    Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://chocolatey.org/install.ps1'))

    # Установка XAMPP через Chocolatey
    choco install xampp -y

    # Путь к XAMPP
    $xamppPath = "C:\xampp"

    # Запуск XAMPP Control Panel
    Start-Process -FilePath "$xamppPath\xampp-control.exe"
    Start-Sleep -Seconds 5

    # Отправка клавиш для нажатия кнопки Start Apache
    [Windows.Forms.SendKeys]::SendWait("{TAB}")
    Start-Sleep -Seconds 1
    [Windows.Forms.SendKeys]::SendWait("{TAB}")
    Start-Sleep -Seconds 1
    [Windows.Forms.SendKeys]::SendWait("{ENTER}")
    Start-Sleep -Seconds 5

    # Отправка клавиш для нажатия кнопки Start MySQL
    [Windows.Forms.SendKeys]::SendWait("{TAB}")
    Start-Sleep -Seconds 1
    [Windows.Forms.SendKeys]::SendWait("{TAB}")
    Start-Sleep -Seconds 1
    [Windows.Forms.SendKeys]::SendWait("{ENTER}")

    $mysqlPath = "C:\xampp\mysql\bin\mysql.exe"  # Путь к утилите mysql
    $databaseName = "polyclinic"  # Имя новой базы данных

    # Подключение к MySQL под пользователем и создание базы данных
    & "$mysqlPath" -u root -e "CREATE DATABASE IF NOT EXISTS $databaseName;"

    # Загрузка данных в базу данных из файла
    Get-Content "C:\Users\k1\Documents\polyclinic.sql" | & "$xamppPath\mysql\bin\mysql.exe" -u root $databaseName

    # Переменные
    $siteName = "ComHe"  # Имя сайта
    $webRoot = "$xamppPath\htdocs\$siteName"  # Путь к корневой папке сайта

    # Создание папки для сайта в htdocs
    New-Item -ItemType Directory -Path $webRoot -Force

    $userPath = "C:\Users\k1\Documents\12"  # Путь к папке пользователя

    # Копирование файлов сайта в $webRoot
    Copy-Item -Path "$userPath\*" -Destination $webRoot -Recurse

    # Открытие браузера с вашим сайтом
    Start-Process -FilePath "http://localhost/$siteName"
}

do {
    Show-Menu
    $choice = Read-Host "Введите номер действия (или 'q' для выхода)"

    switch ($choice) {
        '1' {
            Archive-Files
        }
        '2' {
            Sort-Files
        }
        '3' {
            Deploy-Site
        }
        'q' {
            break
        }
        default {
            Write-Host "Неверный выбор. Попробуйте еще раз."
        }
    }
} while ($choice -ne 'q')
