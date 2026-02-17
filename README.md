# Multi-Channel Content Processor (MCCP)

## Descripción de la Solución
Esta aplicación es un procesador de contenido que permite centralizar la distribución de mensajes a través de múltiples canales (Email, Slack, SMS Legacy). La arquitectura ha sido diseñada siguiendo estándares, aplicando **Arquitectura Hexagonal** y **Domain Driven Development (DDD)** para garantizar escalabilidad, mantenibilidad y desacoplamiento técnico.

### Objetivos Técnicos Cumplidos
*   **Arquitectura Hexagonal:** Separación estricta entre Reglas de Negocio (Domain), Casos de Uso (Application), Implementaciones Técnicas (Infrastructure) y Capa de Presentación (UI).
*   **Domain-Driven Design (DDD):** Uso exhaustivo de patrones tácticos.
*   **Resiliencia Crítica:** Implementación de flujo de parada inmediata si el procesamiento de IA falla.
*   **Distribución Independiente:** Garantía de que el fallo de un canal (ej. Slack) no bloquee el envío exitoso de otros canales (ej. Email).

---

## Patrones de Diseño y Mejores Prácticas Aplicadas

### 1. Domain Layer (El Corazón)
*   **Value Objects:** Se han encapsulado los tipos primitivos en objetos con semántica de negocio y validación propia.
*   **Enums:** Uso de `DeliveryStatus` para eliminar literales "mágicos" en el estado de los envíos.
*   **Custom Exceptions:** Implementación de excepciones con nombre propio para cada contexto de fallo.

### 2. UI & Web Layer
*   **Form Requests:** La validación de entrada se cebntraliza en FormRequest a `StoreMessageRequest`, con la única intenction de mantener el controlador delgado y enfocado únicamente en orquestar la petición.
*   **Inertia.js + React:** Una experiencia de usuario moderna y reactiva sin sacrificar la potencia del backend en Laravel.

### 3. Infrastructure & Config
*   **Variable de Entorno:** Todas las constantes técnicas (URLs de APIs, Keys, Timeouts) están centralizadas en `config/services.php` y alimentadas por el archivo `.env`.
*   **Mapeo de Datos:** Los repositorios Eloquent se encargan de transformar las entidades de dominio en modelos de base de datos y viceversa.

---

## Estructura del Proyecto
```text
app/
├── Domain/
│   ├── Entities/       # Modelos puros de negocio (Message, DeliveryLog)
│   ├── ValueObjects/   # Objetos inmutables (Identity, Title, Content, etc.)
│   ├── Enums/          # Enumerados (DeliveryStatus)
│   ├── Exceptions/     # Excepciones de negocio (AIProcessingException)
│   ├── Repositories/   # Interfaces de persistencia
│   └── Services/       # Interfaces de servicios externos (AI, Channels)
├── Application/
│   └── ProcessAndDistributeContent.php  # Caso de Uso (Orquestador)
├── Infrastructure/
│   ├── AI/             # Cliente de Google Gemini API
│   ├── Channels/       # Implementaciones de Slack, Email y SMS
│   ├── Persistence/    # Repositorios Eloquent de PostgreSQL
│   └── Exceptions/     # Excepciones técnicas (ConfigurationException)
└── UI/
    ├── Http/
    │   ├── Controllers/# Controladores limpios
    │   └── Requests/   # Validadores FormRequest
    └── Resources/      # Transformadores de datos (API)
```

---

## Configuración y Ejecución (Docker)

### 1. Levantar el Entorno
```bash
docker-compose up -d --build
```

### 2. Instalación de Dependencias
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

### 3. Configuración del Entorno (.env)
Asegúrate de configurar las siguientes variables en el archivo `.env`:
```dotenv
DB_CONNECTION=pgsql
DB_HOST=db
DB_DATABASE=mccp_db
DB_USERNAME=user
DB_PASSWORD=password

# Integraciones de IA
GEMINI_API_KEY=tu_api_key_aqui
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent

# Canales Externos
SLACK_WEBHOOK_URL=https://webhook.site/tu_uuid_aqui
```

### 4. Preparar Base de Datos y Assets
```bash
docker-compose exec app php artisan migrate
docker-compose exec app npm run build
```

---

## Evidencias de Funcionamiento
*   **Logs Técnicos:** Se consulta `storage/logs/laravel.log` para verificar la generación del **XML para SMS Legacy (SOAP)** y el payload del **Email**.
*   **Slack Webhook:** Se debe configurar una URL de Webhook.site(https://webhook.site), para valdiar el canal de slack y ver las notificaciones en tiempo real.
*   **Dashboard:** Para visualziar el dashboard se debe acceder a `http://localhost:8000/dashboard` donde esta el historial completo de los mensajes enviados a traves d elos canales, los resúmenes generados por la IA y el estatus individual de cada intento de envío.
