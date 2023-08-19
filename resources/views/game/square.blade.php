<div id="{{ $key . $number }}" class="col-md-1 square border p-0
        @switch($squareData['status'])
            @case(9)
                bg-secondary
                @break
            @case(0)
                bg-primary
                @break
            @case(1)
                bg-primary {{-- change to "bg-sussess" to cheat --}}
                @break
            @case(2)
                bg-warning
                @break
            @case(3)
                bg-danger
                @break
            @default
                bg-primary
        @endswitch"
    onclick="hitSquare(this)"></div>