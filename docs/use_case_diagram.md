# Diagrama de Casos de Uso - StayTuned

## Descripción del Sistema
StayTuned es una plataforma de red social musical que permite a los usuarios crear playlists, compartir contenido musical, formar comunidades y seguir a otros usuarios con intereses musicales similares.

## Actores del Sistema

### 👤 **Usuario**

---

## 🎯 **CASOS DE USO PRINCIPALES**

### 🔐 **GESTIÓN DE AUTENTICACIÓN**
```
┌─────────────────────────────────────┐
│          AUTENTICACIÓN              │
├─────────────────────────────────────┤
│ • Registrarse                       │
│ • Iniciar Sesión                    │
│ • Cerrar Sesión                     │
│ • Recuperar Contraseña              │
│ • Autenticación 2FA                 │
│ • Verificar Email                   │
└─────────────────────────────────────┘
```

### 👥 **GESTIÓN DE PERFIL**
```
┌─────────────────────────────────────┐
│            PERFIL                   │
├─────────────────────────────────────┤
│ • Ver Perfil Propio                 │
│ • Editar Perfil                     │
│ • Actualizar Biografía              │
│ • Cambiar Foto de Perfil            │
│ • Configurar Privacidad             │
│ • Ver Perfil de Otros Usuarios      │
└─────────────────────────────────────┘
```

### 🎵 **GESTIÓN DE PLAYLISTS**
```
┌─────────────────────────────────────┐
│           PLAYLISTS                 │
├─────────────────────────────────────┤
│ • Crear Playlist                    │
│ • Ver Mis Playlists                 │
│ • Editar Playlist                   │
│ • Eliminar Playlist                 │
│ • Buscar Canciones en Spotify       │
│ • Agregar Canción a Playlist        │
│ • Remover Canción de Playlist       │
│ • Configurar Playlist Pública/Privada│
│ • Ver Playlists de Otros Usuarios   │
└─────────────────────────────────────┘
```

### 📝 **GESTIÓN DE PUBLICACIONES**
```
┌─────────────────────────────────────┐
│         PUBLICACIONES               │
├─────────────────────────────────────┤
│ • Crear Publicación                 │
│ • Ver Mis Publicaciones             │
│ • Editar Publicación                │
│ • Eliminar Publicación              │
│ • Dar Like a Publicación            │
│ • Quitar Like de Publicación        │
│ • Buscar Contenido Musical Spotify  │
│ • Adjuntar Contenido Musical        │
│ • Categorizar Publicación           │
└─────────────────────────────────────┘
```

### 🏘️ **GESTIÓN DE COMUNIDADES**
```
┌─────────────────────────────────────┐
│          COMUNIDADES                │
├─────────────────────────────────────┤
│ • Crear Comunidad                   │
│ • Ver Mis Comunidades               │
│ • Editar Comunidad                  │
│ • Eliminar Comunidad                │
│ • Configurar Comunidad Privada/Pública│
│ • Unirse a Comunidad Pública        │
│ • Solicitar Unirse a Comunidad Privada│
│ • Salir de Comunidad                │
│ • Buscar Comunidades Privadas       │
│ • Descubrir Comunidades Públicas    │
└─────────────────────────────────────┘
```

### 👑 **ADMINISTRACIÓN DE COMUNIDADES**
```
┌─────────────────────────────────────┐
│      ADMIN COMUNIDADES              │
├─────────────────────────────────────┤
│ • Ver Solicitudes de Membresía      │
│ • Aprobar Solicitud de Membresía    │
│ • Rechazar Solicitud de Membresía   │
│ • Ver Miembros de Comunidad         │
│ • Remover Miembro de Comunidad      │
│ • Gestionar Permisos de Miembros    │
└─────────────────────────────────────┘
```

### 📝 **PUBLICACIONES EN COMUNIDADES**
```
┌─────────────────────────────────────┐
│    POSTS EN COMUNIDADES             │
├─────────────────────────────────────┤
│ • Crear Post en Comunidad           │
│ • Ver Posts de Comunidades          │
│ • Comentar en Posts de Comunidad    │
│ • Dar Like a Posts de Comunidad     │
└─────────────────────────────────────┘
```

### 💬 **SISTEMA DE COMENTARIOS**
```
┌─────────────────────────────────────┐
│          COMENTARIOS                │
├─────────────────────────────────────┤
│ • Crear Comentario                  │
│ • Editar Comentario                 │
│ • Eliminar Comentario               │
│ • Responder a Comentario            │
│ • Ver Comentarios de Publicación    │
└─────────────────────────────────────┘
```

### 🤝 **SISTEMA DE SEGUIMIENTO**
```
┌─────────────────────────────────────┐
│         SEGUIMIENTO                 │
├─────────────────────────────────────┤
│ • Seguir Usuario                    │
│ • Dejar de Seguir Usuario           │
│ • Ver Mis Seguidos                  │
│ • Ver Mis Seguidores                │
│ • Ver Seguidos de Otro Usuario      │
│ • Ver Seguidores de Otro Usuario    │
└─────────────────────────────────────┘
```

### 🔍 **EXPLORACIÓN Y DESCUBRIMIENTO**
```
┌─────────────────────────────────────┐
│        EXPLORACIÓN                  │
├─────────────────────────────────────┤
│ • Explorar Usuarios                 │
│ • Buscar Usuarios por Nombre        │
│ • Filtrar Usuarios                  │
│ • Ver Dashboard Principal           │
│ • Ver Publicaciones de Seguidos     │
│ • Ver Publicaciones de Comunidades  │
└─────────────────────────────────────┘
```

### 🎵 **INTEGRACIÓN SPOTIFY**
```
┌─────────────────────────────────────┐
│       SPOTIFY INTEGRATION           │
├─────────────────────────────────────┤
│ • Buscar Canciones en Spotify       │
│ • Buscar Artistas en Spotify        │
│ • Buscar Albums en Spotify          │
│ • Obtener Información de Tracks     │
│ • Previsualizar Canciones           │
└─────────────────────────────────────┘
```

### ⚙️ **MODERACIÓN DE CONTENIDO**
```
┌─────────────────────────────────────┐
│        MODERACIÓN                   │
├─────────────────────────────────────┤
│ • Ver Logs de Moderación            │
│ • Filtrar Logs por Tipo             │
│ • Filtrar Logs por Usuario          │
│ • Revisar Contenido Reportado       │
└─────────────────────────────────────┘
```

---

## 🔄 **RELACIONES ENTRE CASOS DE USO**

### **Dependencias Principales:**
- **Crear Publicación** → *extiende* → **Buscar Contenido Musical Spotify**
- **Crear Playlist** → *extiende* → **Buscar Canciones en Spotify**
- **Unirse a Comunidad Privada** → *incluye* → **Solicitar Membresía**
- **Administrar Comunidad** → *requiere* → **Ser Propietario de Comunidad**
- **Comentar Publicación** → *requiere* → **Estar Autenticado**
- **Seguir Usuario** → *requiere* → **Estar Autenticado**

### **Flujos de Navegación:**
1. **Dashboard** → Ver publicaciones de seguidos y comunidades
2. **Explorar Usuarios** → Seguir/Dejar de seguir → Ver perfil
3. **Mis Comunidades** → Crear/Administrar → Ver solicitudes
4. **Playlists** → Crear → Buscar Spotify → Agregar canciones
5. **Publicaciones** → Crear → Adjuntar música → Categorizar

---

## 📊 **ESTADÍSTICAS Y CONTADORES**
El sistema mantiene contadores automáticos de:
- Número de seguidores/seguidos por usuario
- Número de playlists por usuario  
- Número de publicaciones por usuario
- Número de miembros por comunidad
- Número de canciones por playlist
- Número de likes por publicación

---

## 🛡️ **RESTRICCIONES Y REGLAS DE NEGOCIO**

### **Comunidades Privadas:**
- Solo miembros pueden ver contenido
- Requieren solicitud de membresía
- Solo el propietario puede aprobar/rechazar solicitudes

### **Comunidades Públicas:**
- Cualquiera puede unirse
- Contenido visible para todos los usuarios

### **Publicaciones:**
- Pueden ser independientes o asociadas a comunidades
- Solo miembros pueden publicar en comunidades
- Sistema de likes y comentarios

### **Playlists:**
- Pueden ser públicas o privadas
- Integración con API de Spotify para búsqueda
- Los usuarios pueden ver playlists públicas de otros

Esta arquitectura de casos de uso refleja un sistema complejo de red social musical con funcionalidades avanzadas de comunidades, gestión de contenido y integración con servicios externos como Spotify.
