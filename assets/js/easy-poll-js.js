function LoadPollResult(p){
	jQuery('#poll_'+p).hide();
	jQuery('#poll_ans_'+p).show();
}
function LoadPollForm(p){
	jQuery('#poll_ans_'+p).hide();
	jQuery('#poll_'+p).show();
}
function updatePollStatus(){
	jQuery.ajax({
	type: "POST",
	data: { action: "updatePollStatus", poll_nonce_field: jQuery('#poll_nonce_field').val(), p_status: jQuery('#p_status').val(), p_id: jQuery('#p_id').val() }
	})
	.done(function( res ) {
		alert(res);
	});
}
function updatePollEndDate(){
	jQuery.ajax({
	type: "POST",
	data: { action: "updatePollEndDate", poll_nonce_field: jQuery('#poll_nonce_field').val(), p_end: jQuery('#p_end').val(), p_id: jQuery('#p_id').val() }
	})
	.done(function( res ) {
		alert(res);
	});
}
function openPollShare( url ){
	window.open( url, "social share", "location=1,status=1,scrollbars=1,width=800,height=600" );
}
function confirmRemove(){
	var con = confirm( 'Are you sure to remove this?');
	if( con ){
		return true;
	} else {
		return false;
	}
}