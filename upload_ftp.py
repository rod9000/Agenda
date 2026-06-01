import ftplib
import os

# Credenciais FTP
ftp_host = "ftpupload.net"
ftp_user = "if0_41967135"
ftp_pass = "tiUzMXg7kcrfp"

# Arquivos a fazer upload
files = [
    ("resources/views/admin/appointments/detail_modal.blade.php", "/htdocs/resources/views/admin/appointments/detail_modal.blade.php"),
    ("resources/views/admin/appointments/modal.blade.php", "/htdocs/resources/views/admin/appointments/modal.blade.php"),
    ("resources/views/admin/appointments/index.blade.php", "/htdocs/resources/views/admin/appointments/index.blade.php"),
    ("resources/views/layouts/app.blade.php", "/htdocs/resources/views/layouts/app.blade.php"),
    (".env.ftp", "/htdocs/.env"),
]

try:
    # Conectar ao FTP
    ftp = ftplib.FTP(ftp_host, ftp_user, ftp_pass)
    print(f"✓ Conectado ao FTP: {ftp_host}")
    
    # Fazer upload de cada arquivo
    for local_file, remote_file in files:
        if os.path.exists(local_file):
            with open(local_file, 'rb') as f:
                ftp.storbinary(f'STOR {remote_file}', f)
            print(f"✓ Upload: {local_file} → {remote_file}")
        else:
            print(f"✗ Arquivo não encontrado: {local_file}")
    
    # Desconectar
    ftp.quit()
    print(f"\n✓ Upload completo!")
    
except Exception as e:
    print(f"✗ Erro: {e}")
