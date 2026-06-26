# MINERIA - Sistema de Registro y Certificación Minera

Sistema de gestión de certificados y formularios para operadores mineros, desarrollado para el **Gobierno Autónomo Departamental de Beni (GAD BENI), Bolivia** — Dirección de Minería, Energía e Hidrocarburos.

---

## Descripción

MINERIA permite a la autoridad departamental:

- Registrar **empresas operadoras mineras** con sus datos legales y representantes
- Emitir **Certificados de Operador Minero (C.O.M.)** con firma oficial y código único
- Gestionar **Formularios 101 (DDMEH)** de extracción y transporte de minerales
- Generar documentos oficiales en PDF con **códigos QR** de verificación
- Controlar el acceso por roles (Administrador / Funcionario / Usuario)

---

## Stack tecnológico

| Componente | Tecnología |
|-----------|-----------|
| Framework | Laravel 8.x |
| PHP | ^7.3 / ^8.0 |
| Base de datos | MySQL |
| Panel de administración | TCG Voyager ^1.6 |
| Autenticación API | Laravel Sanctum |
| Generación PDF | DomPDF + HTML2PDF |
| Códigos QR | SimpleSoftwareIO QrCode |
| Frontend | Bootstrap (vía Voyager) + Laravel Mix |

---

## Requisitos

- PHP >= 7.3
- Composer
- Node.js >= 14 + npm
- MySQL >= 5.7
- Extensiones PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, GD

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone <repo-url> mineria
cd mineria

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias frontend
npm install && npm run dev

# 4. Configurar el entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar base de datos en .env
DB_DATABASE=mineria
DB_USERNAME=root
DB_PASSWORD=secret

# 6. Ejecutar migraciones y seeders
php artisan migrate --seed

# 7. Enlace de almacenamiento
php artisan storage:link

# 8. Servir la aplicación
php artisan serve
```

> Credenciales por defecto del administrador: `admin@admin.com` / `password`

---

## Uso con Docker

```bash
docker build -t mineria .
docker run -p 8000:8000 mineria
```

---

## Estructura principal

```
app/
├── Http/Controllers/    # CertificateController, Form101Controller, CompanyController
├── Models/              # Company, Certificate, Form101, Signature, TypeMineral, Code, User
└── Providers/           # AppServiceProvider (campos Voyager, HTTPS forzado)

database/
├── migrations/          # 17 migraciones
└── seeders/             # Datos iniciales (roles, firmas, permisos)

resources/views/
├── certificates/        # Formulario, listado e impresión de certificados
├── company/             # Formulario y listado de empresas
└── form101/             # Formulario, listado e impresión del Form101

routes/
├── web.php              # Rutas web (panel admin + impresión pública)
└── api.php              # API con autenticación Sanctum
```

---

## Roles de usuario

| Rol | Permisos |
|-----|---------|
| `admin` | Acceso completo a todas las empresas, certificados y formularios |
| `funcionario` | Solo visualiza formularios de su empresa asignada |
| `user` | Acceso básico |

---

## Documentación adicional

- [Manual de Usuario](docs/manual-usuario.md)
- [Manual Técnico](docs/manual-tecnico.md)

---

## Licencia

Uso interno — Gobierno Autónomo Departamental de Beni, Bolivia.
