{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/admin/logs.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-file-text me-2 text-primary"></i>
                Laravel Logs Viewer
            </h2>
            <div class="d-flex align-items-center">
                <div class="btn-group me-3">
                    <a href="{{ route('logs.download') }}" class="btn btn-info btn-sm">
                        <i class="bi bi-download me-1"></i> Download
                    </a>
                    <button onclick="clearLogs()" class="btn btn-warning btn-sm">
                        <i class="bi bi-trash me-1"></i> Clear Logs
                    </button>
                    <button onclick="toggleAutoRefresh()" class="btn btn-success btn-sm" id="autoRefreshBtn">
                        <i class="bi bi-arrow-clockwise me-1"></i> Auto Refresh: OFF
                    </button>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-clock me-1"></i>
                    Last updated: <span id="lastUpdated">{{ now()->format('Y-m-d H:i:s') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('logs.view') }}" class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">FILTER LOGS</label>
                                    <input type="text" name="filter" class="form-control"
                                           placeholder="Filter logs (e.g., PAYMENT, WEBHOOK, ERROR)"
                                           value="{{ $filter ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-muted">JUMLAH BARIS</label>
                                    <select name="lines" class="form-control">
                                        <option value="50" {{ ($lines ?? 100) == 50 ? 'selected' : '' }}>Last 50 lines</option>
                                        <option value="100" {{ ($lines ?? 100) == 100 ? 'selected' : '' }}>Last 100 lines</option>
                                        <option value="200" {{ ($lines ?? 100) == 200 ? 'selected' : '' }}>Last 200 lines</option>
                                        <option value="500" {{ ($lines ?? 100) == 500 ? 'selected' : '' }}>Last 500 lines</option>
                                        <option value="1000" {{ ($lines ?? 100) == 1000 ? 'selected' : '' }}>Last 1000 lines</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-muted">AKSI</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-funnel me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('logs.view') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-1"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Quick Filter Buttons -->
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-muted mb-2">QUICK FILTERS</label>
                            <div class="btn-group-sm d-flex flex-wrap gap-2" role="group">
                                <a href="{{ route('logs.view', ['filter' => 'PAYMENT']) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-credit-card me-1"></i>Payment Logs
                                </a>
                                <a href="{{ route('logs.view', ['filter' => 'WEBHOOK']) }}"
                                   class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-webhook me-1"></i>Webhook Logs
                                </a>
                                <a href="{{ route('logs.view', ['filter' => 'ERROR']) }}"
                                   class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Error Logs
                                </a>
                                <a href="{{ route('logs.view', ['filter' => 'XENDIT']) }}"
                                   class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-bank me-1"></i>Xendit Logs
                                </a>
                                <a href="{{ route('logs.view', ['filter' => 'CREATE XENDIT INVOICE']) }}"
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-receipt me-1"></i>Invoice Creation
                                </a>
                                <a href="{{ route('logs.view', ['filter' => 'HANDLING PAYMENT SUCCESS']) }}"
                                   class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-check-circle me-1"></i>Payment Success
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Log Content -->
                        <div class="position-relative">
                            <!-- Status Bar -->
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-success me-2">
                                        <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                                    </div>
                                    <span class="small text-muted">Live Monitoring Active</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <span class="me-3">
                                        Filter: <strong>{{ $filter ?: 'All' }}</strong>
                                    </span>
                                    <span class="me-3">
                                        Lines: <strong>{{ $lines ?? 100 }}</strong>
                                    </span>
                                    <span>
                                        <i class="bi bi-info-circle me-1"></i>
                                        Use <kbd>Ctrl+R</kbd> to refresh
                                    </span>
                                </div>
                            </div>

                            <!-- Terminal-style Log Display -->
                            <div class="position-relative">
                                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                                     style="background: #2d3748; color: #e2e8f0;">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex gap-1 me-3">
                                            <div class="rounded-circle bg-danger" style="width: 12px; height: 12px;"></div>
                                            <div class="rounded-circle bg-warning" style="width: 12px; height: 12px;"></div>
                                            <div class="rounded-circle bg-success" style="width: 12px; height: 12px;"></div>
                                        </div>
                                        <span class="small fw-semibold">laravel.log</span>
                                    </div>
                                    <div class="small text-muted">
                                        Environment: {{ app()->environment() }} | PHP {{ PHP_VERSION }}
                                    </div>
                                </div>

                                <div id="logContent" class="log-terminal">{{ $logContent ?? 'No logs found' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .log-terminal {
            background: #1a202c;
            color: #00ff41;
            padding: 20px;
            font-family: 'Fira Code', 'Monaco', 'Consolas', 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.4;
            max-height: 600px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
            border-radius: 0 0 8px 8px;
            position: relative;
        }

        .log-terminal::-webkit-scrollbar {
            width: 12px;
        }

        .log-terminal::-webkit-scrollbar-track {
            background: #2d3748;
        }

        .log-terminal::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 6px;
        }

        .log-terminal::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }

        /* Log Level Colors */
        .log-terminal {
            color: #e2e8f0;
        }

        /* Error logs */
        .log-terminal:has-text("ERROR"),
        .log-terminal:has-text("Exception"),
        .log-terminal:has-text("Fatal") {
            color: #fc8181;
        }

        /* Warning logs */
        .log-terminal:has-text("WARNING"),
        .log-terminal:has-text("WARN") {
            color: #f6e05e;
        }

        /* Info logs */
        .log-terminal:has-text("INFO") {
            color: #63b3ed;
        }

        /* Debug logs */
        .log-terminal:has-text("DEBUG") {
            color: #9ae6b4;
        }

        /* Payment related logs */
        .log-terminal:has-text("PAYMENT"),
        .log-terminal:has-text("WEBHOOK") {
            color: #fbb6ce;
        }

        /* Timestamp highlighting */
        .log-terminal:has-text("[2024-") {
            color: #cbd5e0;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .btn-sm {
            border-radius: 6px;
        }

        kbd {
            background-color: #4a5568;
            color: #e2e8f0;
            border: 1px solid #2d3748;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 11px;
        }

        .badge {
            border-radius: 6px;
        }

        /* Animation for auto refresh button */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .fa-spin, .bi-arrow-clockwise.spinning {
            animation: spin 2s linear infinite;
        }

        /* Hover effects */
        .btn:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        /* Loading states */
        .btn.loading {
            position: relative;
            color: transparent;
        }

        .btn.loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>

    <!-- Scripts -->
    <script>
        let autoRefreshInterval = null;
        let isAutoRefresh = false;
        const currentFilter = '{{ $filter ?? '' }}';
        const currentLines = {{ $lines ?? 100 }};

        function toggleAutoRefresh() {
            const btn = document.getElementById('autoRefreshBtn');
            const icon = btn.querySelector('i');

            if (isAutoRefresh) {
                // Stop auto refresh
                clearInterval(autoRefreshInterval);
                btn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Auto Refresh: OFF';
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-success');
                isAutoRefresh = false;
                console.log('Auto refresh stopped');
            } else {
                // Start auto refresh
                autoRefreshInterval = setInterval(() => {
                    refreshLogs();
                }, 5000); // Refresh every 5 seconds

                btn.innerHTML = '<i class="bi bi-arrow-clockwise spinning me-1"></i> Auto Refresh: ON';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-danger');
                isAutoRefresh = true;
                console.log('Auto refresh started');
            }
        }

        function refreshLogs() {
            console.log('Refreshing logs...');

            // Add loading state
            const logContent = document.getElementById('logContent');
            const originalContent = logContent.textContent;

            fetch('{{ route("logs.stream") }}?' + new URLSearchParams({
                filter: currentFilter,
                lines: currentLines
            }))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.content) {
                    logContent.textContent = data.content;
                    document.getElementById('lastUpdated').textContent = new Date(data.timestamp).toLocaleString('id-ID');

                    // Auto scroll to bottom
                    logContent.scrollTop = logContent.scrollHeight;

                    // Flash effect to show update
                    logContent.style.opacity = '0.7';
                    setTimeout(() => {
                        logContent.style.opacity = '1';
                    }, 200);
                }
            })
            .catch(error => {
                console.error('Error refreshing logs:', error);
                // Show error message briefly
                logContent.textContent = 'Error loading logs: ' + error.message + '\n\n' + originalContent;
            });
        }

        function clearLogs() {
            if (confirm('Are you sure you want to clear all logs?')) {
                const btn = event.target;
                const originalHtml = btn.innerHTML;
                btn.classList.add('loading');

                fetch('{{ route("logs.clear") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btn.classList.remove('loading');
                    if (data.success) {
                        document.getElementById('logContent').textContent = 'Logs cleared successfully\n\nWaiting for new logs...';
                        document.getElementById('lastUpdated').textContent = new Date().toLocaleString('id-ID');

                        // Show success message
                        showNotification('Logs cleared successfully', 'success');
                    } else {
                        showNotification('Failed to clear logs: ' + (data.error || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    btn.classList.remove('loading');
                    console.error('Error:', error);
                    showNotification('Failed to clear logs', 'error');
                });
            }
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Auto scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const logContent = document.getElementById('logContent');
            logContent.scrollTop = logContent.scrollHeight;
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + R untuk refresh manual
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                refreshLogs();
                showNotification('Logs refreshed manually', 'info');
            }

            // Ctrl + Shift + C untuk clear logs
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                clearLogs();
            }

            // Ctrl + Shift + A untuk toggle auto refresh
            if (e.ctrlKey && e.shiftKey && e.key === 'A') {
                e.preventDefault();
                toggleAutoRefresh();
            }

            // Escape untuk stop auto refresh
            if (e.key === 'Escape' && isAutoRefresh) {
                toggleAutoRefresh();
                showNotification('Auto refresh stopped', 'info');
            }
        });

        // Add click to copy functionality
        document.getElementById('logContent').addEventListener('click', function() {
            if (window.getSelection().toString().length === 0) {
                navigator.clipboard.writeText(this.textContent).then(() => {
                    showNotification('Logs copied to clipboard', 'success');
                }).catch(() => {
                    showNotification('Failed to copy logs', 'error');
                });
            }
        });

        // Update timestamp every second
        setInterval(() => {
            if (!isAutoRefresh) {
                const now = new Date();
                const timeDiff = Math.floor((now - new Date(document.getElementById('lastUpdated').textContent)) / 1000);
                if (timeDiff > 0) {
                    const suffix = timeDiff === 1 ? ' second ago' : ' seconds ago';
                    // Could add relative time display here
                }
            }
        }, 1000);
    </script>
</x-app-layout>
