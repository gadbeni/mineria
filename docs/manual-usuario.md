# Manual de Usuario — Sistema MINERIA

**Versión:** 1.0 (borrador inicial)
**Organización:** Gobierno Autónomo Departamental de Beni — Dirección de Minería, Energía e Hidrocarburos

---

> Este manual describe cómo utilizar el sistema para registrar empresas, emitir certificados y gestionar formularios de extracción. Se irá enriqueciendo con capturas de pantalla y procedimientos detallados en versiones posteriores.

---

## 1. Acceso al sistema

Abrir el navegador e ingresar la dirección del sistema. En la pantalla de inicio de sesión ingresar el correo electrónico y contraseña proporcionados por el administrador.

El sistema redirige automáticamente al panel de administración (`/admin`).

---

## 2. Roles de usuario

| Rol | Qué puede hacer |
|-----|----------------|
| Administrador | Acceso total: gestión de empresas, certificados, formularios, usuarios y catálogos |
| Funcionario | Solo puede consultar y registrar formularios 101 de su empresa asignada |

---

## 3. Gestión de empresas

### 3.1 Registrar una empresa

1. En el menú lateral, ingresar a **Empresas**.
2. Hacer clic en **Agregar empresa**.
3. Completar los campos requeridos:
   - **NIT** — número de identificación tributaria (único en el sistema)
   - **NIM** — número de identificación minera
   - **Razón social**
   - **Código de operador minero**
   - **Actividad** — tipo de actividad minera
   - **Representante legal**, **CI** y **teléfono**
   - **Municipio** e información de operación
4. Hacer clic en **Guardar**.

El sistema crea automáticamente un usuario de acceso para el representante de la empresa.

### 3.2 Consultar empresas

En el listado de empresas se pueden buscar por razón social, NIT o representante.

---

## 4. Certificados de Operador Minero (C.O.M.)

### 4.1 Emitir un certificado

1. Ir a **Certificados** en el menú.
2. Hacer clic en **Nuevo certificado**.
3. Seleccionar la empresa, la firma del funcionario responsable y completar:
   - Fechas de vigencia (inicio y fin)
   - Código de operador minero
4. Guardar. El sistema asigna un código único `COM-XXXXXX`.

### 4.2 Imprimir un certificado

En el listado de certificados, hacer clic en el ícono de impresión del registro deseado. Se abre la vista de impresión con el documento oficial que incluye:
- Encabezado del GAD Beni
- Datos de la empresa y del operador
- Código QR para verificación
- Firma del funcionario autorizado

Usar la función de impresión del navegador (`Ctrl+P`) para imprimir o guardar como PDF.

---

## 5. Formulario 101 (DDMEH)

El Formulario 101 registra cada operación de extracción o transporte de minerales.

### 5.1 Crear un Formulario 101

1. Ir a **Formularios 101** en el menú.
2. Hacer clic en **Nuevo formulario**.
3. Completar los datos de extracción:
   - **Certificado** — seleccionar el C.O.M. de la empresa
   - **Tipo de mineral** y **ley del mineral**
   - **Peso bruto**, **humedad** y **peso neto** (en la unidad configurada)
   - **Lote**, **municipio**, **localidad** y **código de área minera**
4. Completar los datos de transporte:
   - **Origen**, **punto intermedio** y **destino final**
   - **Medio de transporte** y **matrícula del vehículo**
   - Datos del conductor: nombre, número de licencia
   - Datos del encargado de transporte: nombre y CI
5. Agregar observaciones si corresponde.
6. Guardar. El sistema asigna el código `DDMEH-XXXXXX`.

### 5.2 Buscar formularios

El listado permite filtrar por:
- Nombre de empresa o representante
- NIT de la empresa
- Tipo de mineral
- Código del formulario

Los funcionarios solo ven los formularios de su empresa.

### 5.3 Imprimir Formulario 101

En el listado, hacer clic en el ícono de impresión del registro correspondiente. El documento incluye código QR para trazabilidad.

---

## 6. Preguntas frecuentes

**¿Qué hago si el NIT ya existe?**
El sistema no permite NIT duplicado. Verificar si la empresa ya está registrada buscando en el listado.

**¿Puedo eliminar un certificado o formulario?**
Solo el administrador puede eliminar registros. Los registros eliminados quedan en el sistema por auditoría pero no aparecen en los listados normales.

**¿Cómo verifico un certificado por QR?**
Escanear el código QR del documento impreso con cualquier lector de QR. Se mostrará la información del certificado.

---

> Este manual se ampliará con capturas de pantalla y flujos detallados en la siguiente versión.
