@extends('layouts.base')

@section('content')
	<label for="task" class="control-label">Add your number and be notified of the apocalypse</label>

	<form action="<?php echo "http://".$_SERVER['HTTP_HOST']."/register" ?>" method="POST" class="form-inline">

		<div class="form-group row">
			<div class="col-sm-10">
				<input type="text" class="form-control" name="name" id="name" placeholder="Name" autofocus>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-10">
				<input type="text" class="form-control" name="number" id="number" placeholder="UK Phone Number (+44...)">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-danger">Register</button>
			</div>
		</div>

	</form>

	<br>
	<label for="task" class="control-label">OR add additional testimonies from a registered number</label>
	<br>

	<form action="<?php echo "http://".$_SERVER['HTTP_HOST']."/update" ?>" method="POST" class="form-inline">

		<div class="form-group row">
			<div class="col-sm-10">
				<input type="text" class="form-control" name="number" id="number" placeholder="UK Phone Number (+44...)">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-warning">Update</button>
			</div>
		</div>

	</form>

	<br>
	<b class="control-label">{{ isset($message) ? $message : "" }}</b>
	<br>

@endsection