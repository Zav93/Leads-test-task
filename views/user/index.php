<?php
use yii\helpers\Url;
$user = Yii::$app->user->identity;

?>
<div id="success_message" class="alert-success alert hidden">
	<button type="button" class="close">×</button>
	<div id="message"></div>
</div>
<div id="error_message" class="alert-danger alert hidden">
	<button type="button" class="close">×</button>
	<div id="message"></div>
</div>

<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Edit Personal Info</button>
<a href="#apiKey" id="show-api-key" class="btn btn-primary btn-lg showApiKey" data-toggle="collapse">Show API Key</a>
<a href="#deposit" class="btn btn-primary btn-lg" data-toggle="collapse">Deposit</a>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Personal Info</h4>
			</div>
			<div class="modal-body">
				<input type="text" id="firstName" value="<?= $user->first_name !== null ? $user->first_name : '' ?>" class="form-control" placeholder="First Name" />
			</div>
			<div class="modal-footer">
				<button type="button" onClick="edit()" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="row collapse" id="apiKey" style="margin-top: 10px">
	<div class="form-group col-xs-4">
		<input type="text" value="<?= $user->api_key !== null ? $user->api_key : '' ?>" id="myInput" class="form-control" readonly/>
	</div>

	<div class="form-group col-xs-4">
		<button onclick="copyText()" class="btn btn-primary copyButton">Copy Text</button>
	</div>
</div>
<div class="row collapse" id="deposit" style="margin-top: 10px">
	<div class="form-group col-xs-8">
		<label for="depositAmount">Deposit Amount</label>
		<input type="text" class="form-control" id="depositAmount" placeholder="Enter amount you want to deposit">
	</div>
	<div class="form-group col-xs-8">
		<label for="apiKeyInput">Api Key</label>
		<input type="text" class="form-control" id="apiKeyInput" placeholder="Api key for authorization">
	</div>

	<div class="form-group col-xs-8">
		<button onclick="deposit()" class="btn btn-primary">Submit</button>
	</div>
</div>

<script>
	function copyText() {
		var copyText = document.getElementById("myInput");
		copyText.select();
		document.execCommand("copy");
		if (copyText.value && copyText.value.length) {
			$(".copyButton").html('Copied!');
		}
	}

	function deposit() {
		var depositAmount = document.getElementById("depositAmount");
		var apiKey = document.getElementById("apiKeyInput");
		var data = JSON.stringify({
			depositAmount: depositAmount.value,
			apiKey: apiKey.value
		});
		$('#success_message').addClass('hidden');
		$('#error_message').addClass('hidden');

		$.ajax({
			url: '<?php echo Url::to(['/api/rest/deposit']) ?>',
			dataType: 'json',
			contentType: 'application/json',
			data: data,
			type: "POST",
			success: function(data) {
				$('#nav_balance').text(data.balance);
				$('#success_message').find('#message').html(data.message);
				$('#success_message').removeClass('hidden');
			},
			error: function(data) {
				$('#error_message').find('#message').html(data.responseJSON.message);
				$('#error_message').removeClass('hidden');
			}
		});
	}

	function edit() {
		var firstName = document.getElementById("firstName");
		$.ajax({
			url: '<?php echo Yii::$app->request->baseUrl . '/user/update'?>',
			data: {first_name: firstName.value},
			type: "POST",
			success: function(){
				location.reload();
			},
			error: function(){
				location.reload();
			}
		});
	}

	$(document).ready(function (e) {
		$('#show-api-key').click(function () {
			if($(this).attr('aria-expanded') == 'false') {
				$('.copyButton').text('Copy Text');
			}
		});
		$('#success_message').find('.close').click(function () {
			$('#success_message').addClass('hidden');
		});
		$('#error_message').find('.close').click(function () {
			$('#error_message').addClass('hidden');
		});
	});
</script>