<form method="POST" action="{{ route('dating.step.four.process') }}" class="needs-validation dating-form" novalidate>
    @csrf
    <input type="hidden" id="backStep" value="step-three" />
    <div class="section-heading">
        <h4>Create Account - Step 4</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <ul class="text-danger listErrors text-start mb-2"></ul>
        <div class="col-lg-12">
            <div class="form-group text-start">
                <label for="religion" class="black-text mb-1">What is your religion?</label>
                <select name="religion" id="religion" class="form-select">
                    <option value="" selected>Select Your Religion</option>
                    <option value="adventist">Adventist</option>
                    <option value="agnostic">Agnostic</option>
                    <option value="atheist">Atheist</option>
                    <option value="buddhist">Buddhist / Taoist</option>
                    <option value="Christian LDS">Christian / LDS</option>
                    <option value="Christian Protestant">Christian / Protestant</option>
                    <option value="Christian Other">Christian / Other</option>
                    <option value="hindu">Hindu</option>
                    <option value="jewish">Jewish</option>
                    <option value="Muslim / Islam">Muslim / Islam</option>
                    <option value="Spiritual but not religious">Spiritual but not religious</option>
                    <option value="Other">Other</option>
                </select>
                <div class="invalid-feedback">
                    Select your religion
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-2">
            <div class="form-group text-start">
                <label for="passion" class="black-text mb-1">What are you passionate about?</label>
                <textarea name="passion" id="passion" rows="4" class="form-control passion" placeholder="What are you passionate about?" maxlength="250"></textarea>
                <label class="passion-counter text-muted">Characters left: 250</label>
                <div class="invalid-feedback">
                    Write about your passion here
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-2">
            <div class="form-group text-start">
                <label for="about" class="black-text mb-1">Write About Yourself</label>
                <textarea name="about" id="about" rows="4" class="form-control about" placeholder="Write about yourself" maxlength="250"></textarea>
                <label class="about-counter text-muted">Characters left: 250</label>
                <div class="invalid-feedback">
                    Write about yourself here
                </div>
            </div>
        </div>

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
