<h3>Pembahasan Tryout: {{ $tryout->tryout_name }}</h3>
<hr>

@foreach($results as $index => $r)

    <p><strong>Soal {{ $loop->iteration }}</strong></p>

    {{-- Kategori --}}
    @if($r->question->category)
        <p class="text-muted">
            {{ $r->question->category->category_name }}
        </p>
    @endif

    {{-- Vignette --}}
    @if(!empty($r->question) && !empty($r->question->vignette))
        <p class="pt-2">{!! $r->question->vignette !!}</p>
    @endif

    {{-- Gambar Soal --}}
    @if (!empty($r->image))
        <div class="text-center mb-3">
            <img src="{{ asset($r->image) }}" 
                alt="Gambar Soal" 
                class="img-fluid rounded shadow-sm"
                style="max-width: 100%; height: auto; object-fit: contain;">
        </div>
    @endif

    {{-- Soal dh betul --}}
    <p>{!! $r->question->question_text !!}</p>


    {{-- Pilihan Jawaban --}}
    @foreach (['A','B','C','D','E'] as $option)
        @php
            $optionField = 'option_' . strtolower($option); // option_a, option_b, ...
            $optionText = $r->question->$optionField ?? null;
        @endphp

        @if($optionText)
            <p><strong>{{ $option }}.</strong> {{ $optionText }}</p>
        @endif
    @endforeach

    {{-- Jawaban Benar bisa --}}
    <p>
        <strong>Jawaban Benar:</strong>
        <span style="background-color: #ffff99; font-weight: bold;">
            {{ strtoupper($r->question->correct_option ?? '-') }}. 
            {{ $r->question->{'option_' . strtolower($r->question->correct_option)} ?? '' }}
        </span>
    </p>

    {{-- Pembahasan dh bisa --}}
    <p><strong>Pembahasan:</strong></p> 
    <p>{!! $r->question->explanation !!}</p>

<hr>
@endforeach
