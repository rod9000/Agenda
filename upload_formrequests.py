import ftplib
import os

ftp_host = "ftpupload.net"
ftp_user = "if0_41967135"
ftp_pass = "tiUzMXg7kcrfp"

REMOTE_BASE = "nabiesteticaagenda.freehosting.dev/htdocs"

files = [
    # FormRequest classes
    ("app/Http/Requests/StoreAppointmentRequest.php", "/htdocs/app/Http/Requests/StoreAppointmentRequest.php"),
    ("app/Http/Requests/StoreCustomerRequest.php", "/htdocs/app/Http/Requests/StoreCustomerRequest.php"),
    ("app/Http/Requests/StoreProductRequest.php", "/htdocs/app/Http/Requests/StoreProductRequest.php"),
    ("app/Http/Requests/StoreServiceRequest.php", "/htdocs/app/Http/Requests/StoreServiceRequest.php"),
    ("app/Http/Requests/StoreUserRequest.php", "/htdocs/app/Http/Requests/StoreUserRequest.php"),
    ("app/Http/Requests/UpdateUserRequest.php", "/htdocs/app/Http/Requests/UpdateUserRequest.php"),

    # Updated controllers (now using FormRequests)
    ("app/Http/Controllers/Admin/AppointmentController.php", "/htdocs/app/Http/Controllers/Admin/AppointmentController.php"),
    ("app/Http/Controllers/Admin/CustomerController.php", "/htdocs/app/Http/Controllers/Admin/CustomerController.php"),
    ("app/Http/Controllers/Admin/UserController.php", "/htdocs/app/Http/Controllers/Admin/UserController.php"),
    ("app/Http/Controllers/Admin/ServiceController.php", "/htdocs/app/Http/Controllers/Admin/ServiceController.php"),
    ("app/Http/Controllers/Admin/ProductController.php", "/htdocs/app/Http/Controllers/Admin/ProductController.php"),

    # Loyalty migrations (if not already on server)
    ("database/migrations/2026_06_19_000002_add_points_to_customers_table.php", "/htdocs/database/migrations/2026_06_19_000002_add_points_to_customers_table.php"),
    ("database/migrations/2026_06_19_000003_create_loyalty_rewards_table.php", "/htdocs/database/migrations/2026_06_19_000003_create_loyalty_rewards_table.php"),
    ("database/migrations/2026_06_19_000004_create_loyalty_redemptions_table.php", "/htdocs/database/migrations/2026_06_19_000004_create_loyalty_redemptions_table.php"),
    ("database/migrations/2026_06_19_000005_add_performance_indexes.php", "/htdocs/database/migrations/2026_06_19_000005_add_performance_indexes.php"),

    # Loyalty models
    ("app/Models/LoyaltyReward.php", "/htdocs/app/Models/LoyaltyReward.php"),
    ("app/Models/LoyaltyRedemption.php", "/htdocs/app/Models/LoyaltyRedemption.php"),

    # Loyalty controller
    ("app/Http/Controllers/Admin/LoyaltyController.php", "/htdocs/app/Http/Controllers/Admin/LoyaltyController.php"),

    # Loyalty views
    ("resources/views/admin/loyalty/index.blade.php", "/htdocs/resources/views/admin/loyalty/index.blade.php"),
    ("resources/views/admin/loyalty/create.blade.php", "/htdocs/resources/views/admin/loyalty/create.blade.php"),
    ("resources/views/admin/loyalty/edit.blade.php", "/htdocs/resources/views/admin/loyalty/edit.blade.php"),
    ("resources/views/admin/loyalty/customer.blade.php", "/htdocs/resources/views/admin/loyalty/customer.blade.php"),

    # Stock report + movements views
    ("resources/views/admin/products/stock-report.blade.php", "/htdocs/resources/views/admin/products/stock-report.blade.php"),
    ("resources/views/admin/products/movements.blade.php", "/htdocs/resources/views/admin/products/movements.blade.php"),

    # Commission professional view
    ("resources/views/admin/commissions/professional.blade.php", "/htdocs/resources/views/admin/commissions/professional.blade.php"),

    # Public reschedule view
    ("resources/views/public/reagendar.blade.php", "/htdocs/resources/views/public/reagendar.blade.php"),

    # Migrate confirmation view
    ("resources/views/admin/migrate/confirm.blade.php", "/htdocs/resources/views/admin/migrate/confirm.blade.php"),

    # Recalculate command
    ("app/Console/Commands/RecalculateLoyaltyPoints.php", "/htdocs/app/Console/Commands/RecalculateLoyaltyPoints.php"),

    # Updated Customer model
    ("app/Models/Customer.php", "/htdocs/app/Models/Customer.php"),
]

def full_remote_path(remote_file):
    return '/' + REMOTE_BASE + remote_file[len('/htdocs'):]

def ensure_remote_dir(ftp, remote_file):
    remote_dir = os.path.dirname(remote_file)
    try:
        ftp.cwd(remote_dir)
    except:
        parts = remote_dir.strip('/').split('/')
        current = ''
        for part in parts:
            current += '/' + part
            try:
                ftp.cwd(current)
            except:
                ftp.mkd(current)
                ftp.cwd(current)
        ftp.cwd('/')

try:
    ftp = ftplib.FTP(ftp_host, ftp_user, ftp_pass)
    print(f"Conectado ao FTP: {ftp_host}")

    uploaded = 0
    for local_file, remote_file in files:
        remote_path = full_remote_path(remote_file)
        if os.path.exists(local_file):
            ensure_remote_dir(ftp, remote_path)
            with open(local_file, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
            print(f"OK: {local_file}")
            uploaded += 1
        else:
            print(f"Arquivo nao encontrado: {local_file}")

    ftp.quit()
    print(f"\nUpload completo! {uploaded} arquivos enviados.")

except Exception as e:
    print(f"Erro: {e}")
