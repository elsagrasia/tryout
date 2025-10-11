@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
  <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    	<div class="ps-3">
						<nav aria-label="breadcrumb mb-0 p-0">
              <h5 class="breadcrumb-item active">All Questions — {{ $package->tryout_name ?? 'Tryout' }}</h5>
              <div class="text-muted">
                Duration: {{ $package->duration ?? '-' }} min • Status: {{ isset($package->status) ? ucfirst($package->status) : '-' }}
              </div>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">
                  <button class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#attachCatQModal">
                    Attach Category & Questions
                  </button>
							
						</div>
					</div>
				</div>
				<!--end breadcrumb-->

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th style="width:60px">No</th>      
                  <th style="width:220px">Category</th>
                  <th>Soal Ujian</th>
                  <th style="width:140px" class="text-center">Kunci Jawaban</th>
                  <th style="width:110px" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($questions as $i => $q)
                  <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $q->category->category_name ?? '-' }}</td>

                    <td style="white-space: pre-wrap;">
                      <div class="fw-semibold mb-1">{{ $q->question_text }}</div>
                      <ol type="A" class="mb-0 ps-3">
                        <li>{{ $q->option_a }}</li>
                        <li>{{ $q->option_b }}</li>
                        <li>{{ $q->option_c }}</li>
                        <li>{{ $q->option_d }}</li>
                        <li>{{ $q->option_e }}</li>
                      </ol>
                    </td>

                    <td class="text-center fw-bold">
                      {{ $q->correct_option }}
                      {{-- kalau ingin tampilkan teks opsi juga, uncomment:
                      <div class="small text-muted">
                        @php $opt = strtolower($q->correct_option); @endphp
                        ({{ $q->{'option_'.$opt} }})
                      </div>
                      --}}
                    </td>

                    <td class="text-center">
                      {{-- Hapus = detach dari paket --}}
                      <form action="{{ route('packages.categories.detach', ['package'=>$package->id, 'question'=>$q->id]) }}"
                            method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Detach this question from package?')">
                          <i class="lni lni-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada soal terpasang pada paket ini.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
</div>

{{-- ===================== MODAL: ATTACH CATEGORY + QUESTIONS ===================== --}}
<div class="modal fade" id="attachCatQModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form action="{{ route('packages.categories.attach', $package->id) }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Attach Category & Select Questions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- Select category (existing only) --}}
        <div class="mb-3">
          <label class="form-label fw-semibold">Select Category</label>
          <select id="categorySelect" name="category_id" class="form-select" required>
            <option value="" disabled selected>— Choose Category —</option>
            @foreach($available as $ac)
              <option value="{{ $ac->id }}">{{ $ac->category_name }}</option>
            @endforeach

            {{-- opsional: izinkan pilih yang sudah attached untuk tambah soal lagi --}}
            @foreach($attached as $ac)
              <option value="{{ $ac->id }}"> {{ $ac->category_name }}</option>
            @endforeach
          </select>
          <div class="form-text">After choosing a category, its question bank will appear below.</div>
        </div>

        {{-- Bank questions per category (pre-rendered, toggle by selection) --}}
        @php
          // gabungan supaya section question tersedia untuk semua kategori yang bisa dipilih
          $allForModal = $attached->concat($available)->unique('id')->values();
        @endphp

        @foreach($allForModal as $c)
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
              <div class="alert alert-info mb-0">
                No available questions in this category’s bank (or all already attached to this package).
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-striped align-middle">
                  <thead class="table-light">
                    <tr>
                      <th style="width:5%"></th>
                      <th>Question</th>
                      <th style="width:90px" class="text-center">Key</th>
                    </tr>
                  </thead>
                  <tbody id="box-{{ $c->id }}">
                    @foreach($bank as $q)
                      <tr>
                        <td><input type="checkbox" name="question_ids[]" value="{{ $q->id }}"></td>
                        <td>{{ Str::limit($q->question_text, 120) }}</td>
                        <td class="text-center fw-bold">{{ $q->correct_option }}</td>
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Attach Selected</button>
      </div>
    </form>
  </div>
</div>

{{-- ===================== SCRIPTS ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  // SweetAlert detach (jika kamu sudah include swal)
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-btn');
    if (!btn) return;
    e.preventDefault();
    const link = btn.getAttribute('href');
    if (!window.Swal) { window.location.href = link; return; }
    Swal.fire({
      title: 'Detach this category?',
      text: 'This will remove the category from the package.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, detach',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33'
    }).then((res) => {
      if (res.isConfirmed) window.location.href = link;
    });
  });

  // Modal: preselect category when user clicks "Attach More" button
  const attachModal = document.getElementById('attachCatQModal');
  attachModal?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const preCat = btn?.getAttribute('data-cat');
    const select = document.getElementById('categorySelect');
    if (preCat && select) {
      select.value = preCat;
      select.dispatchEvent(new Event('change'));
    } else if (select) {
      // reset to placeholder
      select.value = '';
      // hide all bank sections
      document.querySelectorAll('.bank-section').forEach(s => s.classList.add('d-none'));
    }
  });

  // Show bank section for selected category
  const categorySelect = document.getElementById('categorySelect');
  const bankSections = document.querySelectorAll('.bank-section');
  categorySelect?.addEventListener('change', function () {
    bankSections.forEach(sec => sec.classList.add('d-none'));
    const on = document.querySelector(`.bank-section[data-category="${this.value}"]`);
    if (on) on.classList.remove('d-none');
  });

  // Select All per category section
  document.querySelectorAll('.selectAll').forEach(el => {
    el.addEventListener('change', function () {
      const target = document.querySelector(this.dataset.target);
      if (!target) return;
      target.querySelectorAll('input[name="question_ids[]"]').forEach(cb => cb.checked = this.checked);
    });
  });

  // Optional success popup
  @if(session('message'))
    if (window.Swal) {
      Swal.fire({ icon:'success', title:'Success', text:'{{ session('message') }}' });
    }
  @endif
});
</script>
@endsection
