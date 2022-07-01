<form method="POST" action="{{ route('dating.step.three.process') }}" class="needs-validation dating-form" novalidate>
    @csrf
    <input type="hidden" id="backStep" value="step-two" />
    <div class="section-heading">
        <h4>Create Account - Step 3</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <ul class="text-danger listErrors text-start mb-2"></ul>
        <div class="col-lg-12">
            <div class="form-group text-start">
                <label for="have_kids" class="black-text mb-1">Do you have kids?</label>
                <select name="have_kids" id="have_kids" class="form-select">
                    <option value="" selected>Select Valid Option</option>
                    <option value="no">No</option>
                    <option value="Yes they live at home">Yes, they live at home</option>
                    <option value="Yes sometimes they live at home">Yes, sometimes they live at home</option>
                    <option value="Yes they kive away from home">Yes, they kive away from home</option>
                </select>
                <div class="invalid-feedback">
                    Do you have kids?
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="form-group text-start">
                <label for="want_kids" class="black-text mb-1">Do you want kids?</label>
                <select name="want_kids" id="want_kids" class="form-select">
                    <option value="" selected>Select Valid Option</option>
                    <option value="definitely">Definitely</option>
                    <option value="someday">Someday</option>
                    <option value="no-way">No way</option>
                </select>
                <div class="invalid-feedback">
                    Do you want kids?
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="form-group text-start">
                <label for="education" class="black-text mb-1">What is your education?</label>
                <select name="education" id="education" class="form-select">
                    <option value="" selected>Select Your Education</option>
                    <option value="High School">High School</option>
                    <option value="Bachelors Degree">Bachelor's Degree</option>
                    <option value="Some College">Some College</option>
                    <option value="Graduate Degree">Graduate Degree</option>
                    <option value="Associate Degree">Associate Degree</option>
                    <option value="PhD Post Doctoral">PhD / Post Doctoral</option>
                </select>
                <div class="invalid-feedback">
                    Select Your Education
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="form-group text-start">
                <label for="do_smoke" class="black-text mb-1">Do You Smoke?</label>
                <select name="do_smoke" id="do_smoke" class="form-select">
                    <option value="" selected>Do You Smoke?</option>
                    <option value="no">No</option>
                    <option value="Yes Occasionally">Yes, Occasionally</option>
                    <option value="Yes Daily">Yes, Daily</option>
                    <option value="Yes trying to quit">Yes, trying to quit</option>
                </select>
                <div class="invalid-feedback">
                    Do you smoke?
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="form-group text-start">
                <label for="do_drink" class="black-text mb-1">Do You Drink?</label>
                <select name="do_drink" id="do_drink" class="form-select">
                    <option value="" selected>Do You Drink?</option>
                    <option value="no">No</option>
                    <option value="Yes Occasionally">Yes, Occasionally</option>
                    <option value="Yes Daily">Yes, Daily</option>
                    <option value="Yes trying to quit">Yes, trying to quit</option>
                </select>
                <div class="invalid-feedback">
                    Do you Drink?
                </div>
            </div>
        </div>

    </div>
    <div class="mt-4">
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
