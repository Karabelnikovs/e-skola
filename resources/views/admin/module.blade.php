@extends('layouts.admin')
@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <div class="card-body">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <a href='/all-modules' class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
                Atpakaļ</a>
            <a href="{{ route('module.delete', $module->id) }}" onclick="return confirm('Vai esat pārliecināts?')"
                class="btn btn-label-info btn-round me-2 mb-3 ms-md-auto">
                Dzēst moduli <i class="fa fa-trash-alt"></i></a>
        </div>
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h1>{{ $module->title_lv }}</h1>
                <h6 class="op-7 mb-2">
                    {{ $module->description_lv }}
                </h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('module.edit', $module->id) }}" class="btn btn-label-info btn-round me-2">Rediģēt</a>
            </div>
        </div>
        <div class="row">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <h3>Tēmas un testi</h3>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('topic.new', $module->id) }}" class="btn btn-label-info btn-round me-2"><i
                            class="fa fa-plus"></i> Jauna
                        tēma</a>
                    <a href="{{ route('test.new', $module->id) }}" class="btn btn-label-info btn-round me-2"><i
                            class="fa fa-plus"></i> Jauns
                        tests</a>
                    <a href="{{ route('dictionary.new', $module->id) }}" class="btn btn-label-info btn-round me-2"><i
                            class="fa fa-plus"></i> Jauna
                        vārdnīca</a>
                </div>
            </div>
            <ul id="items-list" style="list-style-type: none; padding: 20px;">
                @foreach ($items as $item)
                    {{-- @dd($item) --}}
                    <li style="margin: 10px 0; padding: 10px; border-radius: 15px; background-color: #e9e9e9; cursor: move; @if ($item['type'] == 'topic') background-color: #d1e7dd; @elseif($item['type'] == 'test' && $item['test_type'] == 'final') background-color: #f8d7da; @elseif($item['type'] == 'test') background-color: #fff3cd; @endif"
                        data-id="{{ $item['id'] }}" data-type="{{ $item['type'] }}">
                        <div class="d-flex align-items-center flex-column flex-md-row">
                            <h3 class="my-0 mx-2">{{ $item['title'] }} (
                                @if ($item['type'] == 'topic')
                                    Tēma
                                @elseif($item['type'] == 'test')
                                    Tests
                                @elseif($item['type'] == 'dictionary')
                                    Vārdnīca
                                @endif)
                            </h3>
                            @if (isset($item['content_preview']))
                                <p class="ms-md-auto py-2 py-md-0">{!! $item['content_preview'] !!}</p>
                            @endif
                            <div class="ms-md-auto py-2 py-md-0">
                                @if ($item['type'] == 'topic')
                                    <a href="{{ route('topic.edit', [$item['id'], $module->id]) }}"
                                        class="btn btn-label-info btn-round me-2">Rediģēt</a>
                                @elseif ($item['type'] == 'test')
                                    <a href="{{ route('test.edit', [$item['id'], $module->id]) }}"
                                        class="btn btn-label-info btn-round me-2">Rediģēt</a>
                                @elseif ($item['type'] == 'dictionary')
                                    <a href="{{ route('dictionary.edit', [$item['id']]) }}"
                                        class="btn btn-label-info btn-round me-2">Rediģēt</a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#items-list').sortable({
                update: function(event, ui) {
                    console.log('Order updated!');
                }
            });
        });
    </script>
    <script>
        $('#items-list').sortable({
            update: function(event, ui) {
                let order = [];
                $('#items-list li').each(function() {
                    order.push({
                        id: $(this).data('id'),
                        type: $(this).data('type')
                    });
                });

                $.ajax({
                    url: '{{ route('admin.sort.order') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        console.log(response.message);
                    },
                    error: function(err) {
                        alert('Kārtošana neizdevās.');
                    }
                });
            }
        });
    </script>
@endsection
