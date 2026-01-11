@extends('home.layouts.app')

@section('content')
    <section class="ftco-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2 class="mb-3">Berita dengan tag: <span class="badge badge-primary">{{ $tag }}</span></h2>
                </div>
            </div>

            <div class="row d-flex">
                @forelse ($newsList as $news)
                    <div class="col-md-4 d-flex ftco-animate mb-4">
                        <div class="blog-entry align-self-stretch d-flex flex-column" style="width: 100%;">
                            <a href="{{ route('berita.detail', $news->slug) }}" class="block-20" style="background-image: url('{{ $news->thumbnail_url }}'); flex-shrink: 0; height: 250px;">
                            </a>
                            <div class="text p-4 d-block" style="flex: 1; display: flex; flex-direction: column;">
                                <div class="meta mb-3">
                                    <div><a href="#">{{ optional($news->published_at)->translatedFormat('d F Y') }}</a></div>
                                    <div><a href="#">{{ optional($news->user)->name }}</a></div>
                                    <div><a href="#" class="meta-chat"><span class="icon-eye"></span>{{ $news->view_count }}</a></div>
                                </div>
                                <h3 class="heading mt-3" style="height: 3.6em; overflow: hidden; line-height: 1.2em; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    <a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a>
                                </h3>
                                <p style="height: 4.5em; overflow: hidden; line-height: 1.5em; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; margin-top: auto;">
                                    {{ $news->excerpt }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <p>Belum ada berita dengan tag ini.</p>
                    </div>
                @endforelse
            </div>

            @if ($newsList->hasPages())
                <div class="row mt-5">
                    <div class="col text-center">
                        <div class="block-27">
                            {{ $newsList->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
