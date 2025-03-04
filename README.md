# Sistema de Gestión de Portería

## Descripción
Sistema de gestión para control de acceso y registro de entradas/salidas, con módulos de administración, soporte y portería.

## Requisitos del Sistema
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache 2.4 o superior
- Extensiones PHP requeridas:
  - mysqli
  - pdo
  - mbstring
  - openssl
  - json

## Instalación

1. **Clonar el repositorio**
```bash
git clone [URL_DEL_REPOSITORIO]
cd porteria
```

2. **Configurar la base de datos**
- Crear una base de datos MySQL
- Importar el archivo `database/schema.sql`
- Copiar `config/security.php.example` a `config/security.php`
- Actualizar las credenciales de la base de datos en `config/security.php`

3. **Configurar el servidor web**
- Configurar el DocumentRoot a la carpeta pública del proyecto
- Asegurar que el módulo mod_rewrite está habilitado
- Configurar los permisos de archivos:
```bash
chmod 755 -R /ruta/al/proyecto
chmod 700 config/security.php
chmod -R 700 logs/
chmod -R 700 backups/
```

4. **Configurar el certificado SSL**
- Instalar un certificado SSL válido
- Configurar HTTPS en el servidor web
- Actualizar las URLs en la configuración

5. **Inicializar los directorios necesarios**
```bash
mkdir -p logs
mkdir -p backups
chmod 700 logs
chmod 700 backups
```

## Configuración

1. **Variables de Entorno**
- Copiar `.env.example` a `.env`
- Actualizar las variables de entorno según el ambiente

2. **Seguridad**
- Actualizar las claves de seguridad en `config/security.php`
- Configurar los límites de acceso y timeouts
- Establecer las políticas de contraseñas

## Mantenimiento

1. **Backups**
- Los backups se ejecutan automáticamente cada día
- Ubicación: `/backups`
- Retención: 30 días

2. **Logs**
- Los logs se almacenan en `/logs`
- Rotación automática cada 30 días
- Nivel de log configurable en `config/security.php`

## Seguridad

1. **Autenticación**
- Sistema de roles: Soporte, Administración, Portería
- Bloqueo de cuenta después de 3 intentos fallidos
- Sesiones seguras con regeneración periódica

2. **Protección**
- Protección contra CSRF
- Headers de seguridad HTTP
- Sanitización de entrada/salida
- Rate limiting

## Soporte

Para reportar problemas o solicitar soporte:
1. Abrir un issue en el repositorio
2. Contactar al equipo de soporte: [EMAIL]
3. Consultar la documentación en `/docs` 