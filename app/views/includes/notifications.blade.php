@if ($errors->any())
<div class="alert-notification">
	<h4>error</h4>
    <span>Please check the form below for errors</span>
</div>
@endif

@if ($message = Session::get('success'))
<div class="alert-notification">
	<h4>success</h4>
	<span>{{ $message }}</span>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert-notification">
	<h4>error</h4>
	<span>{{ $message }}</span>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert-notification">
	<h4>warning</h4>
	<span>{{ $message }}</span>
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert-notification">
	<h4>info</h4>
	<span>{{ $message }}</span>
</div>
@endif
