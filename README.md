# eysfwis - Sistema de Información Web

Sistema de información desarrollado con **CodeIgniter 4** y base de datos relacional **MariaDB / MySQL** para la gestión integral de entidades persona, sexo, dirección, correo electrónico y estado civil.

---

## 🚀 Requisitos del Sistema

- **PHP**: 8.1 o superior (con extensiones `intl`, `mbstring`, `mysqli`, `json`)
- **Base de Datos**: MariaDB 10.4+ o MySQL 8.0+
- **Servidor Web**: Apache / Nginx
- **Framework**: CodeIgniter 4

---

## 📦 Estructura del Proyecto

- `app/Controllers/`: Controladores de módulos (`Persona`, `Sexo`, `Direccion`, `CorreoElectronico`, `EstadoCivil`, `Home`).
- `app/Models/`: Modelos de acceso a datos y reglas de validación.
- `app/Views/`: Vistas de interfaz de usuario con Bootstrap y layout modular (`layout/main.php`).
- `public/`: Punto de entrada web (`index.php`, assets CSS/JS).
- `database.sql`: Script de exportación de la base de datos `eysfwis`.

---

## ⚙️ Configuración

1. Configurar la URL base en `.env`:
   ```ini
   app.baseURL = 'http://localhost/eysfwis/public/'
   ```

2. Configurar la conexión a la base de datos en `.env`:
   ```ini
   database.default.hostname = localhost
   database.default.database = eysfwis
   database.default.username = root
   database.default.password = PIWIIB1234
   ```

3. Importar la estructura de la base de datos (si es una instalación nueva):
   ```bash
   mysql -u root -p eysfwis < database.sql
   ```

---

## 🌐 Acceso al Sistema

Abrir en el navegador:
```
http://localhost/eysfwis/public/
```
o directamente:
```
http://localhost/eysfwis/
```
