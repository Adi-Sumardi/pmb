<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB System Status Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-healthy { @apply bg-green-100 text-green-800; }
        .status-warning { @apply bg-yellow-100 text-yellow-800; }
        .status-critical { @apply bg-red-100 text-red-800; }
        .metric-card { @apply bg-white rounded-lg shadow p-6 border; }
        .refresh-animation { animation: spin 1s linear infinite; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">PPDB System Status Dashboard</h1>
            <div class="flex items-center justify-between">
                <p class="text-gray-600">Real-time monitoring and performance metrics</p>
                <button id="refreshBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg id="refreshIcon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- System Status Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Overall Status</p>
                        <p id="overallStatus" class="text-2xl font-bold text-gray-900">Loading...</p>
                    </div>
                    <div id="statusBadge" class="px-3 py-1 rounded-full text-sm font-medium">
                        <span id="statusText">Checking...</span>
                    </div>
                </div>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Memory Usage</p>
                        <p id="memoryUsage" class="text-2xl font-bold text-gray-900">--%</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Cache Hit Rate</p>
                        <p id="cacheHitRate" class="text-2xl font-bold text-gray-900">--%</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Avg Response Time</p>
                        <p id="avgResponseTime" class="text-2xl font-bold text-gray-900">--ms</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div id="alertsSection" class="mb-8 hidden">
            <h2 class="text-xl font-bold text-gray-900 mb-4">🚨 System Alerts</h2>
            <div id="alertsList" class="space-y-4">
                <!-- Alerts will be populated here -->
            </div>
        </div>

        <!-- Detailed Metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Database Metrics -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Database Performance</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Active Connections</span>
                        <span id="dbActiveConnections" class="font-semibold">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Cache Hit Ratio</span>
                        <span id="dbCacheHitRatio" class="font-semibold">--%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Database Size</span>
                        <span id="dbSize" class="font-semibold">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Slow Queries</span>
                        <span id="dbSlowQueries" class="font-semibold">--</span>
                    </div>
                </div>
            </div>

            <!-- Cache Metrics -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cache Performance</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Memory Usage</span>
                        <span id="cacheMemoryUsage" class="font-semibold">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Connected Clients</span>
                        <span id="cacheConnectedClients" class="font-semibold">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Keyspace Hits</span>
                        <span id="cacheKeyspaceHits" class="font-semibold">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Keyspace Misses</span>
                        <span id="cacheKeyspaceMisses" class="font-semibold">--</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage and System Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Storage Metrics -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Storage Usage</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Used Space</span>
                        <span id="storageUsed" class="font-semibold">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Free Space</span>
                        <span id="storageFree" class="font-semibold">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Usage Percentage</span>
                        <span id="storagePercentage" class="font-semibold">--%</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div id="storageProgressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Response Time Chart -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Response Time Distribution</h3>
                <canvas id="responseTimeChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>Last updated: <span id="lastUpdated">--</span></p>
            <p>Auto-refresh every 30 seconds</p>
        </div>
    </div>

    <script>
        let refreshInterval;
        let responseTimeChart;

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeChart();
            loadDashboardData();
            startAutoRefresh();

            document.getElementById('refreshBtn').addEventListener('click', function() {
                loadDashboardData();
            });
        });

        function initializeChart() {
            const ctx = document.getElementById('responseTimeChart').getContext('2d');
            responseTimeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Average', 'Median', 'P95', 'P99'],
                    datasets: [{
                        label: 'Response Time (ms)',
                        data: [0, 0, 0, 0],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.5)',
                            'rgba(16, 185, 129, 0.5)',
                            'rgba(245, 158, 11, 0.5)',
                            'rgba(239, 68, 68, 0.5)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function loadDashboardData() {
            const refreshIcon = document.getElementById('refreshIcon');
            refreshIcon.classList.add('refresh-animation');

            fetch('/health/dashboard')
                .then(response => response.json())
                .then(data => {
                    updateOverviewCards(data);
                    updateDetailedMetrics(data);
                    updateAlerts(data.alerts || []);
                    updateResponseTimeChart(data);
                    document.getElementById('lastUpdated').textContent = new Date().toLocaleString();
                })
                .catch(error => {
                    console.error('Error loading dashboard data:', error);
                    showError('Failed to load dashboard data');
                })
                .finally(() => {
                    refreshIcon.classList.remove('refresh-animation');
                });
        }

        function updateOverviewCards(data) {
            const overview = data.overview || {};
            const performance = data.performance || {};

            // Overall status
            const status = overview.status || 'unknown';
            document.getElementById('overallStatus').textContent = status.charAt(0).toUpperCase() + status.slice(1);

            const statusBadge = document.getElementById('statusBadge');
            const statusText = document.getElementById('statusText');
            statusBadge.className = `px-3 py-1 rounded-full text-sm font-medium status-${status}`;
            statusText.textContent = status.charAt(0).toUpperCase() + status.slice(1);

            // Performance metrics
            document.getElementById('memoryUsage').textContent = (performance.memory_usage || 0).toFixed(1) + '%';
            document.getElementById('cacheHitRate').textContent = (performance.cache_hit_rate || 0).toFixed(1) + '%';
            document.getElementById('avgResponseTime').textContent = (performance.avg_response_time || 0).toFixed(0) + 'ms';
        }

        function updateDetailedMetrics(data) {
            const database = data.database || {};
            const cache = data.cache || {};

            // Database metrics
            if (database.connections) {
                document.getElementById('dbActiveConnections').textContent = database.connections.active || '--';
            }
            if (database.performance) {
                document.getElementById('dbCacheHitRatio').textContent = (database.performance.cache_hit_ratio || 0).toFixed(1) + '%';
                document.getElementById('dbSlowQueries').textContent = database.performance.slow_queries || '--';
            }

            // Cache metrics
            document.getElementById('cacheMemoryUsage').textContent = cache.memory_usage || '--';
            document.getElementById('cacheConnectedClients').textContent = cache.connected_clients || '--';
            document.getElementById('cacheKeyspaceHits').textContent = cache.keyspace_hits || '--';
            document.getElementById('cacheKeyspaceMisses').textContent = cache.keyspace_misses || '--';

            // Storage metrics (from performance data)
            if (data.performance) {
                document.getElementById('storagePercentage').textContent = (data.performance.disk_usage || 0).toFixed(1) + '%';

                const storageBar = document.getElementById('storageProgressBar');
                const diskUsage = data.performance.disk_usage || 0;
                storageBar.style.width = diskUsage + '%';

                // Color code storage bar
                if (diskUsage > 95) {
                    storageBar.className = 'bg-red-600 h-2 rounded-full';
                } else if (diskUsage > 85) {
                    storageBar.className = 'bg-yellow-600 h-2 rounded-full';
                } else {
                    storageBar.className = 'bg-green-600 h-2 rounded-full';
                }
            }
        }

        function updateAlerts(alerts) {
            const alertsSection = document.getElementById('alertsSection');
            const alertsList = document.getElementById('alertsList');

            if (alerts.length === 0) {
                alertsSection.classList.add('hidden');
                return;
            }

            alertsSection.classList.remove('hidden');
            alertsList.innerHTML = '';

            alerts.forEach(alert => {
                const alertElement = document.createElement('div');
                const levelColor = alert.level === 'critical' ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50';

                alertElement.className = `border-l-4 ${levelColor} p-4 rounded`;
                alertElement.innerHTML = `
                    <div class="flex">
                        <div class="flex-1">
                            <h4 class="font-semibold">${alert.message}</h4>
                            ${alert.issues ? `<ul class="mt-2 text-sm"><li>• ${alert.issues.join('</li><li>• ')}</li></ul>` : ''}
                        </div>
                        <div class="text-sm text-gray-500">
                            ${new Date(alert.timestamp).toLocaleTimeString()}
                        </div>
                    </div>
                `;
                alertsList.appendChild(alertElement);
            });
        }

        function updateResponseTimeChart(data) {
            // This would be populated with actual response time data
            // For now, using dummy data structure
            const responseData = [0, 0, 0, 0]; // [avg, median, p95, p99]

            responseTimeChart.data.datasets[0].data = responseData;
            responseTimeChart.update();
        }

        function startAutoRefresh() {
            refreshInterval = setInterval(() => {
                loadDashboardData();
            }, 30000); // Refresh every 30 seconds
        }

        function showError(message) {
            // Simple error display - in production you might want a more sophisticated notification system
            console.error(message);
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });
    </script>
</body>
</html>
