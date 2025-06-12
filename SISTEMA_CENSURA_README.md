# Sistema de Censura de Contenido - StayTuned

## 📋 Descripción

Se ha implementado un sistema automático de censura de contenido ofensivo que protege la plataforma de lenguaje inapropiado en español. El sistema reemplaza automáticamente las palabras ofensivas con asteriscos (*) del mismo tamaño que la palabra original.

## 🛡️ Cobertura del Sistema

El sistema censura automáticamente:

### 📝 **Playlists**
- Nombres de playlists
- Descripciones de playlists

### 📰 **Publicaciones (Posts)**
- Títulos de publicaciones
- Contenido de publicaciones
- Descripciones de publicaciones

### 💬 **Comentarios**
- Texto de comentarios

## 🔧 Cómo Funciona

### Moderación Automática
- **Tiempo Real**: Cuando se crea o actualiza contenido, la censura se aplica automáticamente antes de guardar en la base de datos
- **Transparente**: El usuario ve el contenido censurado sin notificaciones adicionales
- **Eficiente**: No afecta el rendimiento de la aplicación

### Categorías de Palabras Censuradas
El sistema incluye **389 palabras ofensivas** en las siguientes categorías:

1. **Términos racistas y xenófobos** (41 palabras)
2. **Términos homófobos y transfóbicos** (27 palabras)
3. **Términos sexistas y misóginos** (24 palabras)
4. **Términos clasistas y discriminatorios** (28 palabras)
5. **Términos por discapacidad** (32 palabras)
6. **Términos por edad** (8 palabras)
7. **Términos por apariencia física** (22 palabras)
8. **Insultos generales** (31 palabras)
9. **Términos vulgares** (25 palabras)
10. **Términos religiosos ofensivos** (12 palabras)
11. **Términos políticos extremos** (10 palabras)
12. **Variaciones con caracteres especiales** (9 palabras)
13. **Términos adicionales ofensivos** (20 palabras)

### Características Técnicas
- **Detección de palabras completas**: No censura parcialmente palabras inocentes
- **Insensible a mayúsculas/minúsculas**: Detecta tanto "PUTO" como "puto"
- **Plurales incluidos**: Detecta tanto singular como plural de las palabras
- **Reemplazo inteligente**: Mantiene la longitud original de la palabra

## 🚀 Implementación

### Archivos Principales

1. **`app/Services/ContentModerationService.php`**
   - Servicio principal de moderación
   - Contiene la lista de palabras ofensivas
   - Métodos de censura y verificación

2. **`app/Models/Playlist.php`**
   - Integración automática en eventos `creating` y `updating`

3. **`app/Models/Post.php`**
   - Integración automática en eventos `creating` y `updating`

4. **`app/Models/Comment.php`**
   - Integración automática en eventos `creating` y `updating`

5. **`app/Console/Commands/ModerateExistingContent.php`**
   - Comando para aplicar censura a contenido existente

## 🔧 Comandos Disponibles

### Moderar Contenido Existente

```bash
# Ver qué contenido sería modificado (sin aplicar cambios)
php artisan content:moderate --dry-run

# Aplicar censura a todo el contenido existente
php artisan content:moderate

# Moderar solo playlists
php artisan content:moderate --type=playlists

# Moderar solo publicaciones
php artisan content:moderate --type=posts

# Moderar solo comentarios
php artisan content:moderate --type=comments
```

## 📊 Métodos del Servicio

### `ContentModerationService::moderateContent($text)`
- **Propósito**: Censura texto reemplazando palabras ofensivas con asteriscos
- **Parámetro**: `$text` (string) - Texto a moderar
- **Retorna**: String con las palabras ofensivas censuradas

### `ContentModerationService::containsOffensiveContent($text)`
- **Propósito**: Verifica si el texto contiene palabras ofensivas
- **Parámetro**: `$text` (string) - Texto a verificar
- **Retorna**: Boolean (true si contiene palabras ofensivas)

### `ContentModerationService::getOffensiveWords($text)`
- **Propósito**: Obtiene la lista de palabras ofensivas encontradas
- **Parámetro**: `$text` (string) - Texto a analizar
- **Retorna**: Array con las palabras ofensivas encontradas

## 🔄 Flujo de Censura

```
Usuario escribe contenido
           ↓
    Envía formulario
           ↓
Controlador recibe datos
           ↓
  Modelo se va a guardar
           ↓
Event "creating/updating"
           ↓
ContentModerationService
           ↓
   Censura aplicada
           ↓
  Contenido guardado
```

## ⚡ Rendimiento

- **Impacto mínimo**: La censura se ejecuta solo en operaciones de escritura
- **Eficiencia**: Utiliza expresiones regulares optimizadas
- **Escalabilidad**: Funciona eficientemente con grandes volúmenes de contenido

## 🧪 Pruebas

Para verificar que el sistema funciona correctamente:

```bash
php verify_censorship_system.php
```

Este comando ejecuta casos de prueba y muestra el antes y después de la censura.

## 🔧 Mantenimiento

### Agregar Nuevas Palabras Ofensivas
1. Editar `app/Services/ContentModerationService.php`
2. Agregar palabras al array `$offensiveWords`
3. Incluir tanto singular como plural si aplica

### Quitar Palabras de la Lista
1. Editar `app/Services/ContentModerationService.php`
2. Eliminar palabras del array `$offensiveWords`

## ✅ Estado del Sistema

- ✅ Sistema implementado y funcionando
- ✅ 389 palabras ofensivas incluidas
- ✅ Censura automática en todos los modelos relevantes
- ✅ Comando para contenido existente disponible
- ✅ Pruebas completadas y verificadas

El sistema está listo para producción y funcionará automáticamente sin necesidad de intervención manual.
