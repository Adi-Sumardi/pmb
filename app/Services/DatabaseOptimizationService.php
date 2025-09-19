<?php

namespace App\Services;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseOptimizationService
{
    /**
     * Optimize database queries with eager loading
     */
    public function optimizeUserQueries()
    {
        return \App\Models\User::with([
            'pendaftar.studentDetail',
            'pendaftar.parentDetail',
            'pendaftar.academicHistory',
            'pendaftar.documents',
            'pendaftar.payments'
        ]);
    }

    /**
     * Get optimized pendaftar query with selective loading
     */
    public function getOptimizedPendaftarQuery(array $relations = [])
    {
        $defaultRelations = ['user', 'studentDetail', 'parentDetail'];
        $relations = array_merge($defaultRelations, $relations);

        return \App\Models\Pendaftar::with($relations)->select([
            'id',
            'user_id',
            'registration_number',
            'status',
            'created_at',
            'updated_at'
        ]);
    }

    /**
     * Implement database connection pooling optimization
     */
    public function optimizeConnectionPool(): void
    {
        // Configure connection pool settings
        config([
            'database.connections.pgsql.options' => array_merge(
                config('database.connections.pgsql.options', []),
                [
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_STRINGIFY_FETCHES => false,
                ]
            )
        ]);
    }

    /**
     * Analyze and log slow queries
     */
    public function enableQueryLogging(): void
    {
        DB::listen(function ($query) {
            if ($query->time > 1000) { // Log queries taking more than 1 second
                Log::channel('performance')->warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                    'connection' => $query->connectionName
                ]);
            }
        });
    }

    /**
     * Create database indexes for optimization
     */
    public function createOptimizationIndexes(): array
    {
        $indexes = [];

        try {
            // Index for user lookups
            if (!$this->indexExists('users', 'users_email_verified_index')) {
                DB::statement('CREATE INDEX CONCURRENTLY users_email_verified_index ON users (email, email_verified_at)');
                $indexes[] = 'users_email_verified_index';
            }

            // Index for pendaftar status queries
            if (!$this->indexExists('pendaftars', 'pendaftars_status_created_index')) {
                DB::statement('CREATE INDEX CONCURRENTLY pendaftars_status_created_index ON pendaftars (status, created_at)');
                $indexes[] = 'pendaftars_status_created_index';
            }

            // Index for payment status queries
            if (!$this->indexExists('payments', 'payments_status_created_index')) {
                DB::statement('CREATE INDEX CONCURRENTLY payments_status_created_index ON payments (status, created_at)');
                $indexes[] = 'payments_status_created_index';
            }

            // Index for document user queries
            if (!$this->indexExists('documents', 'documents_user_type_index')) {
                DB::statement('CREATE INDEX CONCURRENTLY documents_user_type_index ON documents (user_id, document_type)');
                $indexes[] = 'documents_user_type_index';
            }

            // Composite index for student detail searches
            if (!$this->indexExists('student_details', 'student_details_search_index')) {
                DB::statement('CREATE INDEX CONCURRENTLY student_details_search_index ON student_details (pendaftar_id, school_origin, created_at)');
                $indexes[] = 'student_details_search_index';
            }

        } catch (\Exception $e) {
            Log::error('Failed to create database index: ' . $e->getMessage());
        }

        return $indexes;
    }

    /**
     * Check if index exists
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $result = DB::select("
            SELECT 1
            FROM pg_indexes
            WHERE tablename = ? AND indexname = ?
        ", [$table, $indexName]);

        return !empty($result);
    }

    /**
     * Optimize database configuration
     */
    public function optimizePostgreSQLConfig(): array
    {
        $optimizations = [];

        try {
            // Analyze table statistics
            $tables = ['users', 'pendaftars', 'student_details', 'parent_details', 'payments', 'documents'];

            foreach ($tables as $table) {
                DB::statement("ANALYZE {$table}");
                $optimizations[] = "Analyzed table: {$table}";
            }

            // Update table statistics
            DB::statement('VACUUM ANALYZE');
            $optimizations[] = 'Vacuum analyze completed';

        } catch (\Exception $e) {
            Log::error('Database optimization failed: ' . $e->getMessage());
            $optimizations[] = 'Error: ' . $e->getMessage();
        }

        return $optimizations;
    }

    /**
     * Get database performance metrics
     */
    public function getDatabaseMetrics(): array
    {
        try {
            // Connection pool stats
            $connections = DB::select("
                SELECT
                    count(*) as total_connections,
                    count(*) FILTER (WHERE state = 'active') as active_connections,
                    count(*) FILTER (WHERE state = 'idle') as idle_connections
                FROM pg_stat_activity
                WHERE datname = current_database()
            ");

            // Table sizes
            $tableSizes = DB::select("
                SELECT
                    schemaname,
                    tablename,
                    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) as size,
                    pg_total_relation_size(schemaname||'.'||tablename) as bytes
                FROM pg_tables
                WHERE schemaname = 'public'
                ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC
                LIMIT 10
            ");

            // Cache hit ratio
            $cacheStats = DB::select("
                SELECT
                    sum(heap_blks_read) as heap_read,
                    sum(heap_blks_hit) as heap_hit,
                    round(sum(heap_blks_hit) / (sum(heap_blks_hit) + sum(heap_blks_read)) * 100, 2) as cache_hit_ratio
                FROM pg_statio_user_tables
            ");

            // Index usage
            $indexStats = DB::select("
                SELECT
                    schemaname,
                    tablename,
                    indexname,
                    idx_tup_read,
                    idx_tup_fetch
                FROM pg_stat_user_indexes
                WHERE idx_tup_read > 0
                ORDER BY idx_tup_read DESC
                LIMIT 10
            ");

            return [
                'connections' => $connections[0] ?? null,
                'table_sizes' => $tableSizes,
                'cache_stats' => $cacheStats[0] ?? null,
                'index_usage' => $indexStats,
                'timestamp' => now()
            ];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Implement query result chunking for large datasets
     */
    public function processLargeDataset(Builder $query, callable $callback, int $chunkSize = 1000): void
    {
        $query->chunkById($chunkSize, $callback);
    }

    /**
     * Optimize file upload queries
     */
    public function optimizeFileQueries()
    {
        return \App\Models\Document::select([
            'id',
            'user_id',
            'document_type',
            'file_path',
            'file_size',
            'mime_type',
            'created_at'
        ])->where('deleted_at', null);
    }

    /**
     * Create materialized view for reporting
     */
    public function createReportingViews(): array
    {
        $views = [];

        try {
            // Registration statistics view
            DB::statement("
                CREATE MATERIALIZED VIEW IF NOT EXISTS registration_stats AS
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as total_registrations,
                    COUNT(*) FILTER (WHERE status = 'completed') as completed_registrations,
                    COUNT(*) FILTER (WHERE status = 'pending') as pending_registrations
                FROM pendaftars
                GROUP BY DATE(created_at)
                ORDER BY date DESC
            ");
            $views[] = 'registration_stats';

            // Payment statistics view
            DB::statement("
                CREATE MATERIALIZED VIEW IF NOT EXISTS payment_stats AS
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as total_payments,
                    SUM(amount) as total_amount,
                    COUNT(*) FILTER (WHERE status = 'paid') as successful_payments
                FROM payments
                GROUP BY DATE(created_at)
                ORDER BY date DESC
            ");
            $views[] = 'payment_stats';

        } catch (\Exception $e) {
            Log::error('Failed to create materialized view: ' . $e->getMessage());
        }

        return $views;
    }

    /**
     * Refresh materialized views
     */
    public function refreshMaterializedViews(): void
    {
        try {
            DB::statement('REFRESH MATERIALIZED VIEW registration_stats');
            DB::statement('REFRESH MATERIALIZED VIEW payment_stats');

            Log::info('Materialized views refreshed successfully');
        } catch (\Exception $e) {
            Log::error('Failed to refresh materialized views: ' . $e->getMessage());
        }
    }
}
