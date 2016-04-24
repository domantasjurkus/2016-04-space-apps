@extends('layouts.base')

@section('content')

	<label class="control-label">Would you like to leave a testimony for a registered friend?</label>
	<br>
	<br>

	<form action="<?php echo "http://".$_SERVER['HTTP_HOST']."/testimony" ?>" method="POST" class="form-inline">

		<input type="hidden" name="sender_id" value="{{ $sender_id }}">

		<div class="form-group">
			<label for="recipient_id"></label>
			<select class="form-control" id="recipient_id" name="recipient_id">
				@foreach ($users as $user)
					@if ($user->id != $sender_id)
						<option value="{{ $user->id  }}">{{ $user->name }}</option>
					@endif
				@endforeach
			</select>
        </div>

		<div class="form-group row">
			<div class="col-sm-10">
				<input type="text" class="form-control" name="message" id="message" placeholder="Your Final Words" autofocus>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-10">
				<button type="submit" class="btn btn-success">Save Testimony</button>
			</div>
		</div>
	</form>

	<br>
	<b class="control-label">{{ isset($message) ? $message : "" }}</b>
	<br>

@endsection