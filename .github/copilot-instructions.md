# NowOne WordPress Theme - AI Coding Guidelines

## Architecture Overview
This is a custom WordPress theme for a creative portfolio site featuring music, movies, and artwork creations. Key components:
- **Custom Post Types**: `creation` (with ACF `creation_type`: music/movie/artwork) and `portfolio`
- **Taxonomies**: `genre_music`, `genre_movie`, `genre_artwork` for categorization
- **URL Structure**: Clean URLs like `/music/`, `/movie/{slug}` via custom rewrites in `inc/rewrite.php`
- **ACF Integration**: Fields defined in `acf-json/` with conditional logic based on `creation_type`

## File Organization
- **PHP Templates**: Root-level files (e.g., `archive-creation.php`) with logic in `inc/` and modular parts in `template-parts/`
- **Styling**: SCSS in `src/scss/` (foundation/layout/component/project layers) compiled to `assets/css/app.css`
- **Scripts**: JS in `assets/js/` with jQuery dependencies enqueued in `inc/enqueue.php`
- **ACF**: JSON-synced fields in `acf-json/` for version control

## Key Patterns
- **Conditional Template Loading**: Use ACF `creation_type` to load specific templates, e.g., `get_template_part('template-parts/creation/single', $type)` in `single-creation.php`
- **Query Modification**: Filter `creation` archives by `creation_type` meta in `pre_get_posts` hook (see `inc/rewrite.php`)
- **Permalink Customization**: Override `post_type_link` for `creation` posts to `/{type}/{slug}` format
- **Asset Enqueueing**: Use `filemtime()` for cache-busting in `inc/enqueue.php`

## Development Workflow
- **SCSS Compilation**: Edit files in `src/scss/`, compile to `assets/css/app.css` (no automated build; use VS Code Live Sass Compiler or manual compilation)
- **ACF Changes**: Export JSON to `acf-json/` after field updates for sync
- **Deployment**: Git push triggers GitHub Actions rsync to production server (excludes `.git/`, `.github/`, etc.)

## Conventions
- **Include Structure**: Bootstrap includes in `functions.php` from `inc/` directory
- **Taxonomy Slugs**: Use `genre_{type}` format with URL rewrites replacing `_` with `/`
- **Template Hierarchy**: Leverage `locate_template()` for conditional includes, fallback to defaults
- **Meta Queries**: Use `meta_query` for ACF-based filtering in custom queries

## Examples
- Adding a new creation type: Update ACF select in `acf-json/group_*.json`, add rewrite rule in `inc/rewrite.php`, create `template-parts/creation/archive-{type}.php`
- Styling: Follow SCSS layer order - foundation (variables/mixins), layout (header/footer), component (reusable UI), project (page-specific)
- Enqueueing: Add scripts/styles in `inc/enqueue.php` with dependencies and filemtime for versioning</content>
<parameter name="filePath">/Volumes/PS2000W/WebServer/NowOne/Coding/Local/NowOne/app/public/wp-content/themes/NowOne/.github/copilot-instructions.md