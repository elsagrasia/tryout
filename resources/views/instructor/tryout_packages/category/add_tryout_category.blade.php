@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
  <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div >
      <h5 class="mb-1">Manage Packet — {{ $package->tryout_name ?? 'Tryout' }}</h5>
      <div class="text-muted">
        Duration: {{ $package->duration ?? '-' }} min • Status: {{ isset($package->status) ? ucfirst($package->status) : '-' }}
      </div>
    </div>
    <div class="ms-auto">
      <div class="btn-group">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#attachModal">
      Attach Question
    </button>
      </div>
    </div>

  </div>



  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th style="width:60px">No</th>
              <th style="width:220px">Category</th>
              <th>Question Text & Options</th>
              <th style="width:140px" class="text-center">Correct</th>
              <th style="width:110px" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows as $i => $q)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $q->category->category_name ?? '-' }}</td>
                <td >
                  <div class="fw-semibold mb-1">{{ $q->question_text }}</div>
                  <ol type="A" class="mb-0 ps-3">
                    <li>{{ $q->option_a }}</li>
                    <li>{{ $q->option_b }}</li>
                    <li>{{ $q->option_c }}</li>
                    <li>{{ $q->option_d }}</li>
                    <li>{{ $q->option_e }}</li>
                  </ol>
                </td>
                <td class="text-center fw-bold">{{ $q->correct_option }}</td>
                <td class="text-center">
               
                   <a href="{{ route('packages.questions.detach', [
                    'package' => $package->id,
                    'question' => $q->id
                    ]) }}" 
                    class="btn btn-sm btn-danger delete-btn" title="Delete"><i class="lni lni-trash"></i> </a> 
                 
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted">No questions in this package.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- ===================== MODAL: ATTACH QUESTION ===================== --}}
<div class="modal fade" id="attachModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form action="{{ route('packages.questions.attach', $package->id) }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Attach Questions</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- 1) pilih kategori --}}
        <div class="mb-3">
          <label class="form-label fw-semibold">Select Category</label>
          <select id="categorySelect" name="category_id" class="form-select" required>
            <option value="" disabled selected>— Choose Category —</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}">{{ $c->category_name }}</option>
            @endforeach
          </select>
          <div class="form-text">After selecting a category, its question bank appears below.</div>
        </div>

        {{-- 2) daftar question bank per kategori (toggle sesuai pilihan) --}}
        @foreach($categories as $c)
          @php $bank = $bankByCategory[$c->id] ?? collect(); @endphp
          <div class="bank-section d-none" data-category="{{ $c->id }}">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="fw-semibold">Question Bank — {{ $c->category_name }}</div>
              @if(!$bank->isEmpty())
                <label class="form-check">
                  <input type="checkbox" class="form-check-input selectAll" data-target="#box-{{ $c->id }}">
                  <span class="form-check-label">Select All</span>
                </label>
              @endif
            </div>

            @if($bank->isEmpty())
              <div class="alert alert-info mb-0">No available questions for this category.</div>
            @else
              <div class="table-responsive">
                <table class="table table-striped align-middle">
                  <thead class="table-light">
                    <tr>
                      <th style="width:5%"></th>
                      <th>Question</th>
                    </tr>
                  </thead>
                  <tbody id="box-{{ $c->id }}">
                    @foreach($bank as $q)
                      <tr>
                        <td><input type="checkbox" name="question_ids[]" value="{{ $q->id }}"></td>
                        <td>{{ Str::limit($q->question_text, 120) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        @endforeach
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Attach Selected</button>
      </div>
    </form>
  </div>
</div>

{{-- ===================== JS ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const categorySelect = document.getElementById('categorySelect');
  const bankSections = document.querySelectorAll('.bank-section');

  categorySelect?.addEventListener('change', function () {
    bankSections.forEach(sec => sec.classList.add('d-none'));
    const on = document.querySelector(`.bank-section[data-category="${this.value}"]`);
    if (on) on.classList.remove('d-none');
  });

  document.querySelectorAll('.selectAll').forEach(el => {
    el.addEventListener('change', function () {
      const target = document.querySelector(this.dataset.target);
      if (!target) return;
      target.querySelectorAll('input[name="question_ids[]"]').forEach(cb => cb.checked = this.checked);
    });
  });
});
</script>
@endsection
