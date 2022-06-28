@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container single-blog-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="section-padding blog-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <h5>{{ $post->category->title ?? 'Uncategorized' }}</h5>
                            <h1 class="text-white">{{ $post->title ?? '' }}</h1>
                            <p>{{ $post->description ?? '' }}</p>
                        </div>
                        <div class="img-div text-center mt-4">
                            <img
                                src="{{ asset('assets/frontend/images/posts/' . Str::replace(' ', '%20', $post->icon)) }}">
                        </div>
                        <div class="paras mt-4"> {!! $post->content ?? '-' !!}</div>
                        <div class="user-response mt-4">
                            <h4>Add Your Response</h4>
                            <h4 id="message" style="color: red"></h4>
                            <ul class="text-danger listErrors text-start mb-2"></ul>
                            <form class="mt-4 " method="POST" action="{{ route('comments.store') }}"
                                enctype="multipart/form-data" novalidate id='comment'>
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="post_id" value="{{ $post->id }}"></input>
                                    <div class="col-lg-6">
                                        <input type="text" name="name" placeholder="Name*" required>
                                        <div class="invalid-feedback" style="color: white">
                                            Please enter your Name.
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="email" name="email" placeholder="Email*" required>
                                        <div class="invalid-feedback" style="color: white">
                                            Please enter your Email.
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-4">
                                        <textarea rows="6" name="message" placeholder="Message" required></textarea>
                                        <div class="invalid-feedback" style="color: white">
                                            Please enter your Message.
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-3">
                                        <button type="submit">Post Comment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="comments user-response mt-4">
                            <h4>Comments</h4>
                            <div class="row align-items-center mt-4">
                                {{-- <div class="col-lg-2">
                                    <div class="img-div">
                                        <img src="{{ asset('assets/frontend/images/Mask-2.png') }}">
                                    </div>
                                </div> --}}
                                <div class="col-lg-10">
                                    @forelse ($comment as $comments)
                                    <div class="comment-details">
                                            <h6>{{ $comments->name }}</h6>
                                            <p>{{ $comments->comment }}</p>
                                    </div>
                                @empty
                                    <h5>No Comment</h5>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
    </script>
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
    <script>
        $(document).on('submit', '#comment', function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    $.LoadingOverlay("show");
                },
                success: function(response) {
                    $.LoadingOverlay("hide");
                    $("#message").html(response.success);
                    $("#comment")[0].reset();

                },
                error: function(response) {
                    $.LoadingOverlay("hide");
                    $('ul.listErrors').html('');
                    $.each(response.responseJSON.errors, function (key, val) {
                    $('ul.listErrors').append('<li>'+val+'</li>');
                });
                }
            });
        });
    </script>
@endpush
