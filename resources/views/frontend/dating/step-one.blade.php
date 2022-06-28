<form method="POST" action="{{ route('dating.step.one.process') }}" class="needs-validation dating-form" novalidate>
    @csrf
    <div class="section-heading">
        <h4>Create Account</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <ul class="text-danger listErrors text-start mb-2"></ul>
        <div class="col-lg-12">
            <div class="form-group text-start">
                <label for="gender" class="black-text mb-1">Your Gender</label>
                <select name="gender" id="gender" class="form-select" autofocus required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <div class="invalid-feedback">
                    Select Your Gender
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="form-group text-start">
                <label for="age_start" class="black-text mb-1">Between Ages</label>
                <div class="row">
                    <div class="col-sm-5">
                        <select name="age_start" id="age_start" class="form-select" required>
                            @for ($start = 18; $start <= 110; $start++)
                                <option value="{{ $start }}"
                                    @if ($start == 35) {{ 'selected' }} @endif>
                                    {{ $start }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-sm-2 black-text text-center mt-2"> AND </div>
                    <div class="col-sm-5">
                        <select name="age_end" id="age_end" class="form-select" required>
                            @for ($end = 18; $end <= 110; $end++)
                                <option value="{{ $end }}"
                                    @if ($end == 45) {{ 'selected' }} @endif>
                                    {{ $end }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group text-start">
                <label for="zipcode">Zipcode</label>
                <input type="text" id="zipcode" name="zipcode" class="input-mask" data-inputmask="'mask': '99999'"
                    type="tel" placeholder="Zipcode" value="{{ old('zipcode') }}" required />
                <div class="invalid-feedback">
                    Enter Your Zipcode
                </div>
            </div>
        </div>

    </div>
    <div class="mt-4">
        <button type="submit" class="red-button">Next </button>
        {{-- <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy &
            Cookies Policy.</p> --}}
    </div>
</form>
