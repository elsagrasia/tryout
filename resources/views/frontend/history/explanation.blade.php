@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

@php
    if ($finalScore >= 80) {
        $color = 'text-success';
    } elseif ($finalScore >= 60) {
        $color = 'text-warning';   
    } else {
        $color = 'text-danger';
    }
@endphp

<div class="container">
    <div class="breadcrumb-btn-box mb-4">
        <a href="{{ url()->previous() }}" class="btn theme-btn theme-btn-sm-2 "><i class="la la-arrow-left mr-2"></i>Kembali</a>
    </div>
    <h4>{{ $tryoutName }}</h4>

    <div class="row">
        <div class="col-md-3 responsive-column-half align-self-center">
            <div class="mb-30px text-center">
                <p class="fs-18 font-weight-medium mb-10px">Nilai</p>
                <h4 class="fs-60 font-weight-bold mb-10px {{ $color }}">{{ $finalScore }}</h4>          
            </div>
        </div><!-- end col-lg-3 -->
        <div class="col">
            <div class="row">
                {{-- Jawaban benar --}}
                <div class="col-md-6 responsive-column-half">
                    <div class="card card-item dashboard-info-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-element icon-element-md flex-shrink-0 text-white" style="background-color: #15803d;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16.0303 10.0303C16.3232 9.73744 16.3232 9.26256 16.0303 8.96967C15.7374 8.67678 15.2626 8.67678 14.9697 8.96967L10.5 13.4393L9.03033 11.9697C8.73744 11.6768 8.26256 11.6768 7.96967 11.9697C7.67678 12.2626 7.67678 12.7374 7.96967 13.0303L9.96967 15.0303C10.2626 15.3232 10.7374 15.3232 11.0303 15.0303L16.0303 10.0303Z"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75C17.1086 2.75 21.25 6.89137 21.25 12C21.25 17.1086 17.1086 21.25 12 21.25C6.89137 21.25 2.75 17.1086 2.75 12Z"/>
                                </svg>
                            </div>
                            <div class="pl-4">
                                <p class="card-text fs-18">Jawaban Benar</p>
                                <h5 class="card-title pt-2 fs-26">{{ $correctCount }} / {{ $totalQuestions }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Jawaban ragu --}}
                <div class="col-md-6 responsive-column-half">
                    <div class="card card-item dashboard-info-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-element icon-element-md flex-shrink-0 text-white" style="background-color: #F59E0B;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 
                                            10-10S17.514 2 12 2zm0 18c-4.411 
                                            0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 
                                            8zm0-4a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm1.07-3.75c-.66.39-1.07.79-1.07 
                                            1.75h-2c0-1.66.94-2.58 1.93-3.16.75-.44 1.07-.75 1.07-1.34 
                                            0-.78-.67-1.25-1.5-1.25-.82 0-1.5.47-1.5 1.25h-2c0-1.89 
                                            1.56-3.25 3.5-3.25s3.5 1.36 3.5 3.25c0 1.31-.74 1.99-1.43 
                                            2.45z"/>
                                </svg>
                            </div>

                            <div class="pl-4">
                                <p class="card-text fs-18">Jawaban Ragu</p>
                                <h5 class="card-title pt-2 fs-26">{{ $doubtCount }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tidak terjawab --}}
                <div class="col-md-6 responsive-column-half">
                    <div class="card card-item dashboard-info-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-element icon-element-md flex-shrink-0 text-white" style="background-color:#3E5B99;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 
                                            10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 
                                            8-8 8 3.589 8 8-3.589 8-8 8zm-5-9h10v2H7z"/>
                                </svg>
                            </div>

                            <div class="pl-4">
                                <p class="card-text fs-18">Tidak Terjawab</p>
                                <h5 class="card-title pt-2 fs-26">{{ $unansweredCount }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Waktu --}}
                <div class="col-md-6 responsive-column-half">
                    <div class="card card-item dashboard-info-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-element icon-element-md flex-shrink-0 text-white" style="background-color:#9333EA;">
                                <svg class="svg-icon-color-white" xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24,12A12,12,0,0,1,0,12a1,1,0,0,1,2,0A10,10,0,1,0,12,2a1,1,0,0,1,0-2A12.013,12.013,0,0,1,24,12ZM10.277,11H8a1,1,0,0,0,0,2h2.277A1.994,1.994,0,1,0,13,10.277V7a1,1,0,0,0-2,0v3.277A2,2,0,0,0,10.277,11ZM1.827,8.784a1,1,0,1,0-1-1A1,1,0,0,0,1.827,8.784ZM4.221,5.207a1,1,0,1,0-1-1A1,1,0,0,0,4.221,5.207ZM7.779,2.841a1,1,0,1,0-1-1A1,1,0,0,0,7.779,2.841Z"/>
                                </svg>
                            </div>
                            <div class="pl-4">
                                <p class="card-text fs-18">Waktu</p>
                                <h5 class="card-title pt-2 fs-26">{{ gmdate("H:i:s", $elapsed_time) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end row -->
        </div><!-- end col-lg-4 -->
    </div><!-- end row -->

    {{-- =======================
        Filter Bar
    ======================== --}}
    <div class="row mt-2 mb-4">
        <div class="col-md-6 mb-3">
            <span class="fs-14 font-weight-semi-bold">Tipe Jawaban</span>
            <div class="select-container w-100 pt-2">
                <select id="filterType" class="select-container-select" style="width:100%; min-width:250px;">
                    <option value="all">Semua</option>
                    <option value="correct">Jawaban Benar</option>
                    <option value="wrong">Jawaban Salah</option>
                    <option value="doubt">Ragu-ragu</option> 
                    <option value="unanswered">Tidak Dijawab</option>
                </select>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <span class="fs-14 font-weight-semi-bold">Kategori</span>
            <div class="select-container w-100 pt-2">
                <select id="filterCategory" class="select-container-select" style="width:100%; min-width:250px;">
                    <option value="0" selected>Semua</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- =======================
         DAFTAR HASIL SOAL
    ======================== --}}
    <div id="questionContainer">
        @forelse ($results as $index => $result)

            @php
                // Tentukan type utama
                if (empty($result['user_answer'])) {
                    $type = 'unanswered';
                } elseif ($result['is_correct'] == 1) {
                    // Benar → selalu correct meskipun ragu
                    $type = 'correct';
                } elseif (!empty($result['is_doubt'])) {
                    // Salah + ragu
                    $type = 'doubt';
                } else {
                    // Salah saja
                    $type = 'wrong';
                }

                // Flag ragu untuk filter doubt
                $doubtFlag = !empty($result['is_doubt']) ? '1' : '0';

            @endphp


            <section
                class="quiz-ans-wrap pt-30px pb-30px question-item"
                data-type="{{ $type }}"
                data-category="{{ $result['category_id'] ?? 0 }}"
                data-doubt="{{ $doubtFlag }}"
            >

                <div class="container">
                    <div class="quiz-ans-content">
                        <div class="d-flex align-items-center mb-3">
                            <h3 class="fs-22 font-weight-semi-bold">Soal {{ $index + 1 }}</h3>
                        </div>
                        @if(!empty($result['category_id']))
                            @php 
                                $cat = $categories->firstWhere('id', $result['category_id']); 
                            @endphp
                            @if($cat)
                                <p class="text-muted">{{ $cat->category_name }}</p>
                            @endif
                        @endif

                        {{-- Vignette --}}
                        @if(!empty($result['vignette']))
                            <p class="pt-2">{!! $result['vignette'] !!}</p>
                        @endif

                        {{-- Gambar soal --}}
                        @if (!empty($result['image']))
                            <div class="text-center mb-3">
                                <img src="{{ asset($result['image']) }}" 
                                    alt="Gambar Soal" 
                                    class="img-fluid rounded shadow-sm"
                                    style="max-width: 100%; height: auto; object-fit: contain;">
                            </div>
                        @endif

                        {{-- Teks pertanyaan --}}
                        <p class="pt-2 font-weight-semi-bold">
                            {!! nl2br(e($result['question'] ?? '-')) !!}
                        </p>

                        {{-- Pilihan Jawaban --}}
                        <ul class="quiz-result-list pt-4 pl-3 list-unstyled">
                            @foreach (['a','b','c','d','e'] as $opt)
                                @php
                                    $optionText    = $result['options'][$opt] ?? null;
                                    $userAnswer    = strtolower($result['user_answer'] ?? '');
                                    $correctOption = strtolower($result['correct_option'] ?? '');
                                    $isUserAnswer  = $userAnswer === $opt;
                                    $isCorrect     = $correctOption === $opt;
                                @endphp

                                @if($optionText)
                                    <li class="text-black mb-2 d-flex align-items-start">
                                        {{-- Ikon status --}}
                                        @if($isUserAnswer && $isCorrect)
                                            <span class="icon-element icon-element-xs bg-success text-white mr-2 border border-gray">
                                                <i class="la la-check"></i>
                                            </span>
                                        @elseif($isUserAnswer && !$isCorrect)
                                            <span class="icon-element icon-element-xs bg-danger text-white mr-2 border border-gray">
                                                <i class="la la-times"></i>
                                            </span>
                                        @else
                                            <span class="icon-element icon-element-xs mr-2 border border-gray text-dark">
                                                {{ strtoupper($opt) }}
                                            </span>
                                        @endif

                                        <span style="white-space: normal; word-break: break-word;">
                                            {{ $optionText }}
                                        </span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                        {{-- Tampilkan jawaban benar jika salah atau kosong --}}
                        @if(strtolower($result['user_answer']) !== strtolower($result['correct_option']))
                            <p class="pt-2 mb-4">
                                Jawaban Benar:
                                <span class="text-black font-weight-bold">
                                    {{ strtoupper($result['correct_option']) }}.
                                    {{ $result['options'][strtolower($result['correct_option'])] ?? '-' }}
                                </span>
                            </p>
                        @endif

                        {{-- Pembahasan --}}
                        @if(!empty($result['explanation']))
                            <div class="text-black card mb-4 shadow-sm border-0 font-weight-bold p-3">
                                <p class="mb-2">Pembahasan:</p>
                                <p>{!! $result['explanation'] !!}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @empty
            <div class="alert alert-info">Belum ada hasil untuk tryout ini.</div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        <nav aria-label="Page navigation example" class="pagination-box w-auto shadow-sm">
            <ul class="pagination d-inline-flex justify-content-center mb-0" id="paginationContainer"></ul>
        </nav>
    </div>
</div>


<script>
const itemsPerPage = 10;
let currentPage = 1;

function getFilteredQuestions() {
    const type = document.getElementById('filterType').value;
    const category = document.getElementById('filterCategory').value;

    const allQuestions = Array.from(document.querySelectorAll('.question-item'));

    return allQuestions.filter(q => {

        const qType = q.dataset.type;
        const qCategory = q.dataset.category;
        const isDoubt = q.dataset.doubt === "1"; // ragu (benar maupun salah)

        let typeMatch = false;

        if (type === 'correct') {
            // Benar + Ragu → tetap masuk
            typeMatch = (qType === 'correct');
        }

        else if (type === 'wrong') {
            typeMatch = (qType === 'wrong');
        }

        else if (type === 'doubt') {
            // Semua yang ragu: benar atau salah
            typeMatch = isDoubt;
        }

        else if (type === 'unanswered') {
            typeMatch = (qType === 'unanswered');
        }

        else {
            typeMatch = true;
        }

        const categoryMatch = (category === "0" || qCategory === category);

        return typeMatch && categoryMatch;
    });
}


function renderPage() {
    const filtered = getFilteredQuestions();
    const all = document.querySelectorAll('.question-item');

    const totalPages = Math.ceil(filtered.length / itemsPerPage) || 1;

    // Reset display
    all.forEach(q => q.style.display = 'none');

    // Tampilan halaman aktif
    let start = (currentPage - 1) * itemsPerPage;
    let end   = start + itemsPerPage;

    filtered.slice(start, end).forEach(q => {
        q.style.display = '';
    });

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const pagination = document.getElementById('paginationContainer');
    pagination.innerHTML = '';

    // Prev
    const prev = `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goPage(${currentPage - 1}); return false;">
                <i class="la la-arrow-left"></i>
            </a>
        </li>`;
    pagination.insertAdjacentHTML('beforeend', prev);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        pagination.insertAdjacentHTML('beforeend', `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="goPage(${i}); return false;">${i}</a>
            </li>
        `);
    }

    // Next
    const next = `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goPage(${currentPage + 1}); return false;">
                <i class="la la-arrow-right"></i>
            </a>
        </li>`;
    pagination.insertAdjacentHTML('beforeend', next);
}

function goPage(page) {
    const filtered = getFilteredQuestions();
    const totalPages = Math.ceil(filtered.length / itemsPerPage) || 1;

    if (page < 1 || page > totalPages) return;

    currentPage = page;
    renderPage();
}

function applyFilters() {
    currentPage = 1;
    renderPage();
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    renderPage();

    document.getElementById('filterType').addEventListener('change', applyFilters);
    document.getElementById('filterCategory').addEventListener('change', applyFilters);
});
</script>

@endsection
