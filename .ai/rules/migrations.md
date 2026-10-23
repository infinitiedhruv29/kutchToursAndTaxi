# Migrations & Database Rules

**Applies to**: `database/migrations/**`

- **Foreign Keys**: Always declare foreign keys using `$table->foreignId('...')->constrained()->[cascadeOnDelete()|restrictOnDelete()|nullOnDelete()]`.
- **Reversibility**: Every migration must define an explicit `down(): void` method with `Schema::dropIfExists('...')`.
- **Primary Keys**: Use standard auto-incrementing integer IDs (`$table->id()`).
