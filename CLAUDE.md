# CLAUDE.md — Contexto del proyecto MINERIA

Este archivo persiste el contexto del proyecto entre sesiones de Claude Code.

---

## Propósito del sistema

Sistema web para el **Gobierno Autónomo Departamental de Beni (GAD BENI), Bolivia**, Dirección de Minería, Energía e Hidrocarburos. Gestiona el registro de empresas mineras, emisión de certificados de operador (C.O.M.) y formularios de extracción/transporte de minerales (Formulario 101 / DDMEH).

---

## Stack

- **Laravel 8.x** + PHP ^7.3/^8.0
- **MySQL** (soft deletes en todas las entidades principales para auditoría)
- **TCG Voyager ^1.6** como panel de administración (BREAD)
- **Laravel Sanctum** para autenticación API
- **DomPDF** + **HTML2PDF** para generación de documentos
- **SimpleSoftwareIO QrCode** para QR en documentos oficiales
- **Bootstrap** (heredado de Voyager) en frontend

---

## Modelos clave

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Company` | `companies` | Empresa minera (NIT, NIM, razón social, representante) |
| `Certificate` | `certificates` | C.O.M. — certificado de operador minero |
| `Form101` | `form101s` | Formulario DDMEH de extracción/transporte |
| `Signature` | `signatures` | Firmas de funcionarios públicos (lista controlada) |
| `TypeMineral` | `type_minerals` | Catálogo de tipos de mineral |
| `Code` | `codes` | Tabla auxiliar para lookup de códigos de certificado |
| `User` | `users` | Extiende `TCG\Voyager\Models\User`; tiene `company_id` |

---

## Controladores principales

- `CertificateController` — CRUD + impresión con QR (`/admin/certificates`)
- `Form101Controller` — CRUD + búsqueda AJAX + impresión PDF (`/admin/form101s`)
- `CompanyController` — CRUD + creación de usuario asociado (`/admin/companies`)
- `AjaxController` — Validación de código de certificado vía AJAX

---

## Rutas importantes

- `GET /` → redirige a `/admin`
- `GET /certificates/{id}/print` → impresión pública sin autenticación
- Todas las demás rutas bajo `/admin` requieren autenticación Voyager

---

## Roles y permisos

- `admin` — acceso completo
- `funcionario` — solo ve formularios de su empresa (`company_id`)
- `user` — acceso básico

---

## Convenciones de código

- Códigos auto-generados: `COM-XXXXXX` (certificados), `DDMEH-XXXXXX` (Form101)
- Soft deletes activos en Company, Certificate, Form101, Signature, Code, User
- El campo `registerUser_id` registra quién creó cada entidad
- Paginación Bootstrap (`AppServiceProvider::boot()`)
- HTTPS forzado en producción vía header `X-Forwarded-Proto`
- Vistas Voyager personalizadas en `resources/views/vendor/voyager/`
- Campo de formulario Voyager custom: `App\FormFields\registerUserIdFormField`

---

## Archivos de documentación

- [README.md](README.md) — instalación y visión general
- [docs/manual-usuario.md](docs/manual-usuario.md) — guía de uso para funcionarios
- [docs/manual-tecnico.md](docs/manual-tecnico.md) — referencia técnica para desarrolladores

---

## Notas de contexto

- El archivo `resources/views/vendor/voyager/` contiene vistas de Voyager **sobrescritas**; editar con precaución para no romper el panel admin.
- El método `Form101Controller@prinf` tiene un typo intencional (heredado); no renombrar sin actualizar la ruta `form101s.prinf` en `routes/web.php`.
- La firma pre-cargada por seeder corresponde a Nathaly Davilia Antezana (funcionaria GAD Beni).
- Docker disponible vía `Dockerfile` en la raíz.
