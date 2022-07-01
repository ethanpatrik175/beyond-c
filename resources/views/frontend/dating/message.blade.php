<form method="GET" action="#" class="dating-form"
    enctype="multipart/form-data" novalidate>
    @csrf
    <input type="hidden" id="userAvatar"
        @if (Session::has('avatar')) data-add-file="{{ asset('assets/frontend/images/users/' . auth()->user()->id . '/' . Session::get('avatar')) }}" @else data-add-file="" @endif>
    <input type="hidden" id="backStep" value="step-four" />
    <div class="section-heading">
        <h4>Create Account - Done</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <div class="col-sm-12">
            <ul class="text-danger listErrors text-start mb-2"></ul>
        </div>
        <div class="col-sm-12">
            <button style="background: #000; text-transform: uppercase;"><a href="{{route('find.your.date')}}">Redirect to Subscription</a></button>
        </div>
    </div>
</form>
