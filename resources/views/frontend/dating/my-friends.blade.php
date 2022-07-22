<div class="row">
    @forelse ($friends as $user)
        @if (isset($user->dating))
            <div class="col-lg-4 mb-3 testimonial-box-{{ $user->id }}">
                <div class="testimonial-box">
                    <div class="row align-items-center item-{{ $user->id }}">
                        <div class="col-lg-3 col-3">
                            <div class="img-div">
                                @if (isset($user->dating->avatar))
                                    <img src="{{ asset('assets/frontend/images/users/' . $user->id . '/' . Str::of($user->dating->avatar)->replace(' ', '%20')) }}"
                                        alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}"
                                        class="user-avatar">
                                @else
                                    <img src="{{ asset('assets/frontend/images/user.png') }}"
                                        alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}"
                                        class="user-avatar" />
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-9 col-9">
                            <div class="text-box position-relative">
                                <h6>{{ Str::of($user->first_name . ' ' . $user->last_name)->upper() }}
                                </h6>
                                <p>{{ Str::upper($user->dating->relationship_status) }}</p>
                            </div>
                            <p class="mb-0 detail">Gender:
                                {{ Str::of($user->dating->gender)->ucfirst() ?? '' }}</p>
                            <p class="mb-0 detail">Height: {{ $user->dating->height ?? '' }} (cm)
                            </p>
                            <p class="mb-0 detail">Age:
                                {{ \Carbon\Carbon::parse($user->dating->date_of_birth)->age ?? '' }}
                                (Yrs)
                            </p>
                        </div>
                        <div class="col-lg-3 col-3"></div>
                        <div class="col-lg-9 col-9">
                            <hr class="m-0 p-0" />

                            @if ($currentUserDating->isFriendWith($user))
                                <form class="request-form mt-1 d-inline-block" data-item="{{ $user->id }}"
                                    action="{{ route('dating.send.request') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}" />
                                    <input type="hidden" name="action" id="action{{ $user->id }}" value="unfriend" />
                                    <input type="submit" class="btn btn-sm btn-warning" id="btn{{ $user->id }}" value="Unfriend" />
                                </form>
                                <form class="request-form mt-1 d-inline-block" data-item="{{ $user->id }}"
                                    action="{{ route('dating.send.request') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}" />
                                    <input type="hidden" name="action" id="action{{ $user->id }}" value="blockuser" />
                                    <input type="submit" class="btn btn-primary btn-sm" id="btn{{ $user->id }}" value="Block" />
                                </form>
                                <a href="{{url('chat/'.$user->id)}}" class="btn btn-sm btn-primary">Msg</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
        @endif
    @empty
        <div class="col-lg-12 mb-4 text-center text-white">
            <h5>No Friends Found!</h5>
        </div>
    @endforelse
</div>
