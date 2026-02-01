@extends('layouts.master')
@section('title')
    Daftar Berita
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    Daftar Berita
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="card-title mb-1">Daftar Berita</h4>                            </div>
                            <a href="{{ route('news.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Tambah Berita
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal Publish</th>
                                        <th>Penulis</th>
                                        <th>Views</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($news as $item)
                                    <tr>
                                        <th scope="row">{{ ($news->currentPage() - 1) * $news->perPage() + $loop->iteration }}</th>
                                        <td>
                                            <strong>{{ $item->title }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-primary font-size-12">{{ $item->category }}</span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill {{ $item->is_published ? 'bg-success' : 'bg-light' }} font-size-12">
                                                {{ $item->is_published ? 'Terbit' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td>{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</td>
                                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                                        <td>{{ $item->view_count ?? 0 }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('news.edit', $item->id) }}" class="btn btn-sm btn-soft-primary">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('{{ $item->id }}', '{{ $item->title }}')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data berita</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($news->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $news->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
    @section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <!-- Delete confirmation script with SweetAlert2 -->
        <script>
            function confirmDelete(id, title) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Berita \"" + title + "\" akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm-' + id).submit();
                    }
                });
            }
        </script>
        
        <!-- Hidden delete forms for each item -->
        @foreach($news as $item)
            <form id="deleteForm-{{ $item->id }}" action="{{ route('news.destroy', $item->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
