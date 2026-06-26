# Manual Técnico — Sistema MINERIA

**Versión:** 1.0 (borrador inicial)
**Stack:** Laravel 8.x · MySQL · TCG Voyager · PHP ^7.3/^8.0

---

> Documento de referencia técnica para desarrolladores. Se ampliará con diagramas de arquitectura, flujos de datos y decisiones de diseño en versiones posteriores.

---

## 1. Arquitectura general

```
Browser / Cliente
      │
      ▼
routes/web.php  ──►  Middleware (Auth / Voyager)
      │
      ▼
Controllers (app/Http/Controllers/)
      │
      ├── Eloquent Models (app/Models/)
      │         │
      │         └── MySQL (soft deletes en todas las entidades)
      │
      └── Views (resources/views/)
                ├── Blade templates propios
                └── Voyager vendor views (sobrescritas)
```

---

## 2. Modelos y base de datos

### 2.1 Diagrama de relaciones (simplificado)

```
users ──────────────────────────────────────────────────────────┐
  │ company_id                                                   │
  ▼                                                             │
companies (nit, nim, razon, codeMiningOperator, representative) │
  │ id                                                          │
  ▼                                                             │
certificates (company_id, signature_id, code COM-XXXXXX)        │
  │ id                                                          │
  ▼                                                             │
form101s (certificate_id, typeMineral_id, code DDMEH-XXXXXX)   │
                                                                │
signatures ◄────────────────────────────────────────────────────┘
type_minerals ◄── (catálogo de minerales)
codes ◄─── (lookup de códigos de certificado)
```

### 2.2 Tablas propias del sistema

| Tabla | Soft Delete | Descripción |
|-------|-------------|-------------|
| `companies` | Sí | Empresas operadoras |
| `certificates` | Sí | Certificados C.O.M. |
| `form101s` | Sí | Formularios DDMEH |
| `signatures` | Sí | Firmas de funcionarios |
| `type_minerals` | Sí | Catálogo de minerales |
| `codes` | Sí | Lookup de códigos |
| `users` | Sí | Extiende Voyager User |

### 2.3 Migraciones relevantes

Las migraciones en `database/migrations/` siguen orden cronológico. Las tres últimas alteran `form101s`:
- `2024_12_04` — añade `unidaddemedida1` y `medioTransporte1`
- `2025_03_27` — añade `intermedio` (punto intermedio de transporte)

---

## 3. Controladores

### CertificateController
**Archivo:** [app/Http/Controllers/CertificateController.php](../app/Http/Controllers/CertificateController.php)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `index()` | GET /admin/certificates | Lista certificados con relaciones Company y Signature |
| `create()` | GET /admin/certificates/create | Formulario de creación |
| `store()` | POST /admin/certificates | Crea certificado; genera código `COM-XXXXXX` en transacción |
| `print()` | GET /certificates/{id}/print | Vista de impresión pública con QR |
| `destroy()` | DELETE /admin/certificates/{id} | Soft delete del certificado y su código asociado |

### Form101Controller
**Archivo:** [app/Http/Controllers/Form101Controller.php](../app/Http/Controllers/Form101Controller.php)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `index()` | GET /admin/form101s | Lista de formularios |
| `list()` | GET /admin/form101s/ajax/list/{search?} | AJAX paginado; filtra por rol |
| `create()` | GET /admin/form101s/create (implícito) | Formulario de creación |
| `store()` | POST /admin/form101s | Crea Form101; genera código `DDMEH-XXXXXX` |
| `prinf()` | GET /admin/form101s/prinf/{form?} | PDF con QR (**typo en nombre — no renombrar sin actualizar ruta**) |
| `destroy()` | DELETE /admin/form101s/{id} | Soft delete |

### CompanyController
**Archivo:** [app/Http/Controllers/CompanyController.php](../app/Http/Controllers/CompanyController.php)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `store()` | POST /admin/companies/store | Crea empresa y usuario asociado (role_id=2); valida NIT único |
| `ajaxCompany()` | GET /admin/companies/certificate/list | Búsqueda JSON para select2 |

---

## 4. Generación de documentos

### PDF y QR

- **DomPDF** (`barryvdh/laravel-dompdf ^2.0`) — usado en impresión de certificados
- **HTML2PDF** (`spipu/html2pdf ^5.2`) — clase helper en `app/Http/Controllers/HTML2PDF.php`
- **QR Code** (`simplesoftwareio/simple-qrcode ~4`) — incrustado en vistas `print.blade.php` y `prinf.blade.php`

El QR codifica datos del certificado/formulario. Cualquier lector de QR puede verificar la autenticidad del documento.

### Vista de impresión pública

La ruta `GET /certificates/{id}/print` es **pública** (sin autenticación). Esto es intencional para permitir la verificación externa de certificados.

---

## 5. Panel de administración (Voyager)

El sistema usa TCG Voyager ^1.6 como panel BREAD. Las vistas de Voyager están sobrescritas en:

```
resources/views/vendor/voyager/
```

Editar estas vistas con precaución. Si se actualiza el paquete Voyager, revisar si los cambios en las vistas vendor siguen siendo compatibles.

**Campo personalizado:** `App\FormFields\registerUserIdFormField` — registrado en `AppServiceProvider::register()`. Asigna automáticamente el ID del usuario que crea el registro.

---

## 6. Autenticación y roles

- Autenticación web: Voyager (sesiones Laravel)
- Autenticación API: Laravel Sanctum (`routes/api.php`)
- Roles definidos en tabla `roles` (gestionada por Voyager)
- Permisos en `permissions` y `permission_role`
- El modelo `User` extiende `TCG\Voyager\Models\User`
- Filtro por rol en `Form101Controller@list`: si el usuario tiene rol `funcionario`, solo ve los Form101 de su `company_id`

---

## 7. Configuración del entorno

Variables de entorno clave en `.env`:

```env
APP_NAME=Mineria
APP_ENV=production
APP_URL=https://dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mineria
DB_USERNAME=root
DB_PASSWORD=

# Si el servidor está detrás de un proxy/balanceador:
# El forzado HTTPS se hace en AppServiceProvider via X-Forwarded-Proto
```

---

## 8. Seeders de datos iniciales

```bash
php artisan db:seed
```

Seeders relevantes:

| Seeder | Datos que carga |
|--------|----------------|
| `UsersTableSeeder` | Usuario admin (`admin@admin.com` / `password`) |
| `RolesTableSeeder` | Roles admin, funcionario, user |
| `SignaturesTableSeeder` | Firma de Nathaly Davilia Antezana (GAD Beni) |
| `DataTypesTableSeeder` + `DataRowsTableSeeder` | Configuración BREAD de Voyager |

---

## 9. Comandos útiles

```bash
# Limpiar caché de configuración y vistas
php artisan config:clear
php artisan view:clear
php artisan route:clear

# También disponible vía ruta web (solo admin):
# GET /admin/clear-cache

# Descargar logs:
# GET /admin/download/log/{cad?}

# Regenerar autoload tras cambios de clases
composer dump-autoload
```

---

## 10. Consideraciones de seguridad

- El campo `registerUser_id` en cada tabla registra el autor de cada registro para auditoría.
- Soft deletes garantizan que ningún registro se elimine físicamente.
- CSRF activo en todas las rutas POST/DELETE web.
- La ruta de impresión pública (`/certificates/{id}/print`) no requiere autenticación — no exponer datos sensibles adicionales en esa vista.
- Validación de NIT único en `CompanyController@store`.
- No usar `--no-verify` en git hooks ni saltar validaciones de Voyager.

---

> Este manual se ampliará con diagramas UML, flujos de despliegue y guía de pruebas en la siguiente versión.
