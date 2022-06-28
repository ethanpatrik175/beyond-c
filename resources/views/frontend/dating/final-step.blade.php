<form method="POST" action="{{ route('dating.step.four.process') }}" class="needs-validation dating-form" novalidate>
    @csrf
    <input type="hidden" id="backStep" value="step-four" />
    <div class="section-heading">
        <h4>Create Account - Last Step</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <ul class="text-danger listErrors text-start mb-2"></ul>
        


    </div>
    <div class="mt-2">
        <button type="submit" class="red-button">Next </button>
        <div class="row mt-2">
            <div class="col-sm-6 text-start"><a href="javascript:void(0);" class="back">BACK</a></div>
            <div class="col-sm-6 text-end skip">
                <a href="javascript:void(0);" class="skip">SKIP</a>
            </div>    
        </div>    
        {{-- <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy &
            Cookies Policy.</p> --}}
    </div>
</form>
