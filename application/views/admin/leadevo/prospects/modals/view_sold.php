<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewProspectModalLabel">Prospect Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" />
            <!-- Prospect data will be loaded here via AJAX -->
            <div id="prospectDetails">
                <p><strong>First Name:</strong> <span id="prospectFirstName"></span></p>
                <p><strong>Status:</strong> <span id="prospectStatus"></span></p>
                <p><strong>Source:</strong> <span id="prospectAcquisitionChannel"></span></p>
                <p><strong>Campaign ID:</strong> <span id="prospectIndustry"></span></p>
                <p><strong>Sold Price:</strong> <span id="soldPrice"></span></p>
                <p><strong>Invoice ID:</strong> <span id="invoiceId"></span></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#viewProspectModal').on('shown.bs.modal', function () {

            let prospectId = $('#viewProspectModal input[name=id]').val();

            $.ajax({
                url: '<?= admin_url('prospects/get_sold_data') ?>',
                type: 'GET',
                data: { id: prospectId },
                success: function (response) {
                    const res = JSON.parse(response);

                    if (res.error) {
                        alert(res.error);
                        return;
                    }

                    const data = res.prospect;

                   
                    $('#prospectFirstName').text(data.first_name);
                    $('#prospectLastName').text(data.last_name || 'N/A');
                    $('#prospectStatus').text(data.status);
                    $('#prospectAcquisitionChannel').text(data.source_name);
                    $('#soldPrice').text(data.sold_price);
                    $('#invoiceId').text(data.invoice_id);
                    $('#invoiceHash').text(data.invoice_hash);

                    $('#viewProspectModal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Failed to fetch prospect data: ' + errorThrown);
                }
            });
        });
    });
</script>
