<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="row mb-4">
                <!-- Combined Form -->
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Generate Report</h5>
                        </div>
                        <div class="card-body">
                            <form id="reportForm" action="<?= base_url('home/generate_report') ?>" method="post">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                                </div>
                                <input type="hidden" id="report_type" name="report_type" value="">
                                <button type="submit" class="btn btn-primary" onclick="setReportType('pdf')">Generate PDF</button>
                                <button type="submit" class="btn btn-success" onclick="setReportType('excel')">Generate Excel</button>
                                <button type="submit" class="btn btn-info" onclick="setReportType('window')">Generate Windows</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function setReportType(type) {
    document.getElementById('report_type').value = type;
}
</script>
