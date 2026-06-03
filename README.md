# HolaSalta Child Theme

Child theme de WordPress para **HolaSalta.com**, portal de noticias local de Salta, Argentina. Construido sobre [Blocksy](https://creativethemes.com/blocksy/) sin page builders ni frameworks CSS.

---

## Stack

| Capa | Tecnología |
|---|---|
| CMS | WordPress |
| Tema padre | Blocksy |
| PHP | Templates propios (jerarquía WordPress completa) |
| CSS | Plano, sin compilador — `assets/css/holasalta.css` |
| JS | Vanilla, sin jQuery — `assets/js/holasalta.js` |
| Fuentes | Self-hosted woff2 — `assets/fonts/` |
| SEO | Rank Math |
| Cache | LiteSpeed Cache |
| Entorno local | XAMPP — `http://localhost/wp` |

---

## Desarrollo local

1. Clonar en `wp-content/themes/holasalta-child/`
2. Activar el tema desde **WP Admin → Apariencia → Temas**
3. El scaffold inicial corre automáticamente al activar: crea páginas institucionales, categorías editoriales, menús y asigna la portada estática
4. Editar CSS y JS directamente — el versionado usa `filemtime()`, alcanza con `Ctrl+Shift+R` para ver los cambios

---

## Deploy a producción — checklist

### 1. Subir archivos
Copiar la carpeta `holasalta-child/` completa al servidor en `wp-content/themes/`.

### 2. Activar el tema
**WP Admin → Apariencia → Temas → Activar**

Al activar, el scaffold crea automáticamente:
- Páginas: Inicio, Últimas noticias, Quiénes somos, Contacto, Publicidad, Política editorial, Política de privacidad, Términos y condiciones
- Categorías: Salta, Policiales, Nacionales, Deportes, Espectáculos, Internacionales, ¿Sabías que?, Columnas
- Menú principal y menú de footer asignados

### 3. Configurar WordPress
- **Ajustes → Lectura**: Portada estática → Inicio / Entradas → Últimas noticias
- **Ajustes → Permalinks**: Guardar cambios (regenera las reglas de reescritura)

### 4. Configurar LiteSpeed Cache
- **Cache → Cache** → activar Page Cache
- **Optimization → CSS**: activar Minify + Combine
- **Optimization → JS**: activar Minify (no Combine — puede romper scripts de terceros)
- **Media**: activar WebP Replacement y Lazy Load
- **Toolbox → Purge All** después de activar el tema

### 5. Configurar Rank Math
- Activar schema **NewsArticle** para posts
- Completar Open Graph (logo, redes sociales)
- Verificar y enviar el sitemap en Google Search Console
- Conectar Google Analytics 4

### 6. Regenerar miniaturas (solo si hay posts viejos)
Instalar el plugin **Regenerate Thumbnails**, ejecutarlo una vez y desactivarlo. Solo necesario si había posts con imágenes antes de activar el tema.

### 7. Verificar
- [ ] Portada carga el hero con las 4 noticias más recientes
- [ ] Ticker "Último momento" animado
- [ ] Single post: progress bar, breadcrumb, share bar
- [ ] Búsqueda devuelve resultados
- [ ] Footer con menú institucional asignado
- [ ] Sidebar de notas muestra módulos
- [ ] Zonas de publicidad visibles en WP Admin → Apariencia → Widgets

---

## Publicidad

Las zonas publicitarias se gestionan desde **WP Admin → Apariencia → Widgets**.

| Zona | Ubicación | Tamaño sugerido |
|---|---|---|
| Publicidad home arriba | Portada, tope | 728×90 |
| Publicidad home franja | Portada, debajo del tope | 500×160 |
| Publicidad home medio | Portada, zona media | 728×90 |
| Publicidad home cuadrado 1/2/3 | Portada, entre secciones | 450×450 |
| Publicidad home abajo | Portada, pie | 728×90 |
| Publicidad single arriba | Sidebar de notas, tope | 300×250 |
| Publicidad single medio | Sidebar de notas, medio | 300×250 |
| Publicidad dentro de noticia | Tras el 3er párrafo del artículo | 728×90 |
| Publicidad de categorías | Páginas de sección | 728×90 |

Para cada zona: arrastrar un widget **Imagen** (con enlace) o **HTML personalizado** (para códigos de ad networks como AdSense).

---

## Categorías editoriales

Las categorías se definen en `inc/template-tags.php` → `hs_get_editorial_sections()`.

Para agregar o cambiar una categoría:
1. Editar el array en `hs_get_editorial_sections()`
2. Agregar el `hs_setup_category()` correspondiente en `inc/setup-site.php`
3. Agregar el `hs_setup_menu_item()` correspondiente en `inc/setup-site.php`
4. Actualizar las descripciones en `functions.php` → `hs_ensure_site_config()`

---

## Fuentes

Self-hosted en `assets/fonts/` — sin requests externos a Google Fonts.

| Archivo | Fuente | Uso |
|---|---|---|
| `inter-var.woff2` | Inter 400–800 latin | Cuerpo, UI |
| `inter-var-latext.woff2` | Inter 400–800 latin-ext | Cuerpo, UI (caracteres extendidos) |
| `dm-serif-400.woff2` | DM Serif Display latin | Títulos |
| `dm-serif-400-italic.woff2` | DM Serif Display italic latin | Títulos en cursiva |
| `dm-serif-400-latext.woff2` | DM Serif Display latin-ext | Títulos (caracteres extendidos) |
| `dm-serif-400-italic-latext.woff2` | DM Serif Display italic latin-ext | Títulos en cursiva (caracteres extendidos) |

Los `@font-face` están declarados al inicio de `assets/css/holasalta.css`.

---

## Arquitectura

```
holasalta-child/
├── functions.php                   # Bootstrap, assets, widget areas, filtros
├── inc/
│   ├── template-tags.php           # Helpers hs_*(): queries, cards, social, secciones
│   └── setup-site.php              # Scaffold único al activar el tema
├── template-parts/
│   ├── card-post.php               # Card reutilizable: standard / compact / hero
│   ├── section-post-grid.php       # Grid de cards por sección
│   ├── sidebar-news.php            # Sidebar editorial modular
│   ├── ad-slot.php                 # Renderer de publicidad
│   └── related-posts.php          # Notas relacionadas (single)
├── assets/
│   ├── css/holasalta.css           # Estilos (@font-face + variables + componentes)
│   ├── js/holasalta.js             # Ticker, progress bar, header, share, drawer, search
│   └── fonts/                      # Fuentes woff2 self-hosted
├── front-page.php                  # Portada editorial
├── single.php                      # Nota individual
├── category.php                    # Archivo de sección
├── home.php                        # Índice de entradas (últimas noticias)
├── archive.php                     # Archivos de fecha, autor, etiqueta
├── search.php                      # Resultados de búsqueda
├── page.php                        # Páginas institucionales
├── 404.php                         # Página no encontrada
├── header.php                      # Header + drawer de secciones
├── footer.php                      # Footer 3 columnas
└── comments.php                    # Comentarios estilizados
```

---

## Paleta de marca

```css
--hs-primary:   #EC1566   /* Rosa — acentos, activos, labels */
--hs-secondary: #545353   /* Gris — texto secundario, footer */
--hs-text:      #1f1f1f   /* Negro — cuerpo */
--hs-bg:        #ffffff   /* Fondo general */
--hs-soft-bg:   #f6f6f6   /* Fondo suave — cards, zonas alternadas */
--hs-border:    #e5e5e5   /* Bordes */
```

---

## Reglas de desarrollo

- Nunca modificar la carpeta `blocksy/`
- Todo el código custom va en `holasalta-child/`
- Sin Elementor, sin page builders, sin frameworks CSS
- Sin paso de build — editar CSS y JS directamente
- No tocar `wp-config.php` ni la base de datos directamente
- Compatible con Rank Math y LiteSpeed Cache
