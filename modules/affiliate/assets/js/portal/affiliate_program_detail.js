function copy_public_link(){
  "use strict";
  	var link = $('#link_register').val();
    var copyText = document.getElementById("link_register");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert_float('success','Copied!');
}

function copy_product_link(){
  "use strict";
  	var link = $('#link_product').val();
    var copyText = document.getElementById("link_product");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert_float('success','Copied!');
}

function product_detail(product_id, program_id){
  "use strict";
	$.post(site_url + 'affiliate/usercontrol/get_product_detail/'+product_id+'/'+program_id).done(function(response) {
      response = JSON.parse(response);
     
      $('#product_detail_body').html(response);
		$('#commodity_list-add-edit').modal('show');
		var gallery = new SimpleLightbox('.gallery a', {});
    });
}