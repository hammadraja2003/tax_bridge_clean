@extends('admin.layouts.adminlayout')

@section('content')
<div class="container-fluid">
    <div class="row table_setting">
        <div class="col-xxl-12">
            <div class="card">
                {{-- Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laravel Log Viewer</h5>
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" id="searchBox" placeholder="Search logs..."
                            class="form-control form-control-sm" style="width: 180px;">
                        <select id="filterLevel" class="form-select form-select-sm" style="width: 120px;">
                            <option value="">All Levels</option>
                            <option value="ERROR">Error</option>
                            <option value="WARNING">Warning</option>
                            <option value="INFO">Info</option>
                            <option value="DEBUG">Debug</option>
                        </select>
                        <button id="refreshBtn" class="btn btn-success btn-sm">
                            <i class="ti ti-refresh"></i> Refresh
                        </button>
                        <button id="clearBtn" class="btn btn-danger btn-sm">
                            <i class="ti ti-trash"></i> Clear
                        </button>
                    </div>
                </div>
                {{-- Log Body --}}
                <div class="card-body p-0 bg-dark text-light" style="font-family: monospace; font-size: 13px;">
                    <div id="logContainer" class="p-3 overflow-auto"
                        style="min-height: 70vh; max-height: 80vh; white-space: pre-wrap;">
                        {{-- Fallback for no JS --}}
                        @if (!empty($log))
                            {!! nl2br(e($log)) !!}
                        @else
                            <p class="text-muted">No log entries found.</p>
                        @endif
                    </div>
                </div>
                {{-- Footer --}}
                <div class="card-footer d-flex justify-content-between align-items-center text-muted small">
                    <span id="status">Loading logs...</span>
                    <span>Auto-refresh every 15s</span>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- JS Logic --}}
<script>
    const logContainer = document.getElementById('logContainer');
    const refreshBtn = document.getElementById('refreshBtn');
    const clearBtn = document.getElementById('clearBtn');
    const status = document.getElementById('status');
    const searchBox = document.getElementById('searchBox');
    const filterLevel = document.getElementById('filterLevel');
    let allLogs = "";

    
    async function fetchLogs() {
        status.textContent = 'Loading...';
        try {
            const response = await fetch('{{ route('admin.logs.show') }}?lines=300', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            allLogs = data.log || 'No logs found.';
            renderLogs();
            status.textContent = 'Last updated: ' + new Date().toLocaleTimeString();
        } catch (e) {
            logContainer.textContent = '⚠️ Error loading logs.';
            status.textContent = 'Failed to fetch logs.';
        }
    }

    
    function renderLogs() {
        let filtered = allLogs;
        const query = searchBox.value.trim().toLowerCase();
        const level = filterLevel.value;

        if (level) {
            const regex = new RegExp(`\\b${level}\\b`, 'i');
            filtered = filtered.split('\n').filter(line => regex.test(line)).join('\n');
        }
        if (query) {
            filtered = filtered.split('\n').filter(line => line.toLowerCase().includes(query)).join('\n');
        }

        logContainer.innerHTML = highlightLogs(filtered);
    }

    
    function highlightLogs(logs) {
        return logs
            .replace(/\[(.*?)\]/g, '<span class="text-secondary">[$1]</span>')
            .replace(/\bERROR\b/g, '<span class="text-danger fw-bold">ERROR</span>')
            .replace(/\bWARNING\b/g, '<span class="text-warning fw-bold">WARNING</span>')
            .replace(/\bINFO\b/g, '<span class="text-info fw-bold">INFO</span>')
            .replace(/\bDEBUG\b/g, '<span class="text-primary fw-bold">DEBUG</span>');
    }

    
    async function clearLogs() {
        if (!confirm('Are you sure you want to clear all logs?')) return;
        status.textContent = 'Clearing logs...';
        try {
            const response = await fetch('{{ route('admin.logs.clear') }}');
            const data = await response.json();
            alert(data.message);
            await fetchLogs();
        } catch (e) {
            alert('❌ Failed to clear logs.');
        }
    }

    refreshBtn.addEventListener('click', fetchLogs);
    clearBtn.addEventListener('click', clearLogs);
    searchBox.addEventListener('input', renderLogs);
    filterLevel.addEventListener('change', renderLogs);

    setInterval(fetchLogs, 15000);
    fetchLogs();
</script>
@endsection
