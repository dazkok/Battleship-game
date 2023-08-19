@extends('layouts.index')

@section('content')
<div class="w-100 bg-dark vh-100 text-white d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="display-1 text-center mb-3">Battlefield</div>
                    @for ($col = 0; $col <= 10; $col++)
                        <div class="col-md-1 square border p-0 square-text">{{ $col }}</div>
                    @endfor
                </div>
                @foreach ($variables['board'] as $key => $row)
                    <div class="row">
                        <div class="col-md-1 square border p-0 square-text">{{ $key }}</div>
                        @foreach ($row as $number => $squareData)
                            @include('game.square')
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="col-md-5 d-flex flex-column justify-content-between align-items-center px-lg-4">
                <div class=""></div>
                <div>
                    <div id="quest">
                        <div class="display-5 text-center">Ships to be destroyed:</div>
                        <hr/>
                        <div class="">
                            @foreach ($variables['ships'] as $ship)
                                <div class="display-6 d-flex justify-content-between w-100">
                                    <span>{{ $ship->sh_count }}x{{ $ship->sh_name }}</span>
                                    <span class="text-secondary">(1x{{ $ship->sh_size }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="finish" class="d-none">
                        <div class="display-3 text-center text-success">A victory! Congratulations!</div>
                    </div>
                    <hr/>
                    <div class="display-6">
                        Shots have been fired: <span id="shots" class="text-warning">0</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end w-100">
                    <a class="btn btn-danger me-3" href="{{ route('endgame') }}" role="button">End this game</a>
                    <a href="{{ route('home') }}" class="btn btn-light">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerSection')
<script>
    function hitSquare(square) {
        const data = {
            squareNr: square.id
        };

        sendFetch('{{ route('hit') }}', data)
        .then(html => {
            for (const key in html) {
                if (html.hasOwnProperty(key)) {
                    const viewContent = html[key];
                    const element = document.getElementById(key);

                    if (element) {
                        element.innerHTML = viewContent;
                    } else {
                        console.warn('Element with key', key, 'not found.');
                    }
                }
            }
            refreshShots();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function refreshShots() {
        sendFetch('{{ route('shots-count') }}')
        .then(data => {
            document.getElementById('shots').innerHTML = data.shotsCount
            if (data.gameFinished) {
                var finishEl = document.getElementById('finish');
                finishEl.classList.remove('d-none');
                
                var questEl = document.getElementById('quest');
                questEl.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    window.onload = function() {
        refreshShots()
    };
    
</script>
@endsection