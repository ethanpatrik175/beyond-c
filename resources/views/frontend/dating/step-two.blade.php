<form method="POST" action="{{ route('dating.step.two.process') }}" class="needs-validation dating-form" novalidate>
    @csrf
    <input type="hidden" id="backStep" value="step-one" />
    <div class="section-heading">
        <h4>Create Account - Step 2</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <ul class="text-danger listErrors text-start mb-2"></ul>
        <div class="col-lg-12">
            <div class="form-group text-start">
                <label for="date_of_birth" class="black-text mb-1">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" autofocus required />
                <div class="invalid-feedback">
                    Enter Valid Date of Birth
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="form-group text-start">
                <label for="body_type" class="black-text mb-1">How would you describe your body type?</label>
                <select name="body_type" id="body_type" class="form-select">
                    <option value="">Select Body Type</option>
                    <option value="slim-slender">Slim/Slender</option>
                    <option value="athletic-fit">Athletic/Fit</option>
                    <option value="average">About Average</option>
                    <option value="muscular">Muscular</option>
                    <option value="curvy">Curvy</option>
                    <option value="extra-pounds">A few extra pounds</option>
                    <option value="big-and-beautiful">Big and Beautiful</option>
                    <option value="heavyest">Heavyest</option>
                </select>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="form-group text-start">
                <label for="relationship_status" class="black-text mb-1">What is your relationship status?</label>
                <select name="relationship_status" id="relationship_status" class="form-select">
                    <option value="single">Definitely Single</option>
                    <option value="separated">Separated</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed">Widowed</option>
                </select>
            </div>
        </div>

    </div>
    <div class="mt-4">
        <button type="submit" class="red-button">Next </button>
        <div class="row mt-2">
            <div class="col-sm-6 text-start"><a href="javascript:void(0);" class="back">BACK</a></div>
            <div class="col-sm-6 text-end skip"></div>    
        </div>
        {{-- <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy &
            Cookies Policy.</p> --}}
    </div>
</form>
