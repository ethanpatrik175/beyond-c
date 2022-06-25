@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@push('styles')
    <style>
        .page-item.active .page-link {
            background-color: #c00000;
            border-color: #c00000;
        }
        .page-link:hover, .page-link{
            color: #c00000;
        }
    </style>
@endpush

@section('content')
    <div class="main-container blog-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="blogs section-padding">
            <div class="container">
                <div class="row justify-content-center">
                    @forelse ($blogs as $post)
                        <div class="col-lg-3 mb-4">
                            <div class="blog-card">
                                <div class="blog-card-upper">
                                    <div class="img-div">
                                        <a href="{{ route('front.blog.detail', $post->slug) }}"><img
                                                src="{{ asset('assets/frontend/images/posts/' . Str::replace(' ', '%20', $post->icon)) }}"
                                                alt=""></a>
                                    </div>
                                </div>
                                <div class="blog-card-lower">
                                    <div class="blog-category">
                                        <p class="text-white">{{ $post->category->title ?? 'Uncategorized' }}</p>
                                    </div>
                                    <div class="blog-desc">
                                        <h6 class="text-white"><a href="{{ route('front.blog.detail', $post->slug) }}"
                                                title="{{ $post->title ?? '' }}">{{ $post->title ? Str::limit($post->title, 45) : '' }}</a>
                                        </h6>
                                        <p>{{ Str::limit($post->description, 100) }}</p>
                                    </div>
                                    <div class="blog-details">
                                        <p>By <span
                                                class="text-white">{{ Str::upper($post->user->first_name) }} {{ ($post->user->last_name) ? $post->user->last_name : '' }}</span> -
                                            {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }} </p>
                                    </div>
                                    <div class="blog-comments mt-2 d-flex">
                                        <p><i class="fa-solid fa-message"></i> 0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-lg-12 mb-4">
                            <h5><a href="javascript:void(0);">No Post Found!</a></h5>
                        </div>
                    @endforelse

                    <div class="col-sm-12 text-center mt-4">
                        {{-- {!! $events->links() !!} --}}
                        {!! $blogs->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </section>
        <x-testimonial />
        <x-footer />
    </div>
@endsection
