@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
 
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Aturan Poin</h5>
            <form id="myForm" action="{{ route('points.rules.update', $point->id) }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $point->id }}">

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Aktivitas</label>
                    <!-- Aktivitas tetap ditampilkan, namun tidak bisa diubah karena disabled -->
                    <input type="text" name="activity" class="form-control" id="input1" value="{{ $point->activity }}" disabled>
                    
                    <!-- Input hidden untuk mengirim nilai activity ke server -->
                    <input type="hidden" name="activity" value="{{ $point->activity }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Poin</label>
                    <input type="number" name="points" class="form-control" value="{{ $point->points }}" required>
                </div>
                <div>
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control">{{ $point->description }}</textarea>
                </div>            
                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Status</label>
                    <select name="status" class="form-select mb-3" aria-label="Default select example">
                        <option selected="">Pilih Status</option>
                        <option value="active" {{ $point->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $point->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>                

                 <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        <a href="{{ route('points.rules') }}" class="btn btn-secondary px-4">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
